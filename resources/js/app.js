const parser = new DOMParser();

function setBusy(element, busy) {
    if (!element) return;
    element.dataset.busy = busy ? 'true' : 'false';
    if (element.tagName === 'BUTTON') {
        element.disabled = busy;
    }
}

async function replacePageFromResponse(responseText, pushUrl = null) {
    const doc = parser.parseFromString(responseText, 'text/html');
    const nextContent = doc.querySelector('[data-page-content]');
    const currentContent = document.querySelector('[data-page-content]');

    if (!nextContent || !currentContent) return;

    currentContent.innerHTML = nextContent.innerHTML;
    document.title = doc.title || document.title;

    if (pushUrl) {
        window.history.pushState({}, '', pushUrl);
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function fetchAndSwap(url, options = {}, pushUrl = null) {
    const response = await fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'text/html,application/xhtml+xml',
        },
        ...options,
    });

    const text = await response.text();
    await replacePageFromResponse(text, pushUrl);
}

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-async]');
    if (!form) return;

    event.preventDefault();

    const submitter = event.submitter || form.querySelector('button[type="submit"]');
    setBusy(submitter, true);

    try {
        const method = (form.querySelector('input[name="_method"]')?.value || form.method || 'GET').toUpperCase();
        const action = form.action;
        const body = new FormData(form);

        if (method === 'GET') {
            const url = new URL(action);
            new URLSearchParams(body).forEach((value, key) => url.searchParams.set(key, value));
            await fetchAndSwap(url.toString(), { method: 'GET' }, url.toString());
        } else {
            await fetchAndSwap(action, { method: 'POST', body });
        }
    } finally {
        setBusy(submitter, false);
    }
});

document.addEventListener('click', async (event) => {
    const link = event.target.closest('a[data-async-link]');
    if (!link || link.target === '_blank' || event.metaKey || event.ctrlKey) return;

    event.preventDefault();
    await fetchAndSwap(link.href, { method: 'GET' }, link.href);
});

window.addEventListener('popstate', async () => {
    await fetchAndSwap(window.location.href, { method: 'GET' });
});

const modal = document.createElement('div');
modal.className = 'modal-backdrop';
modal.innerHTML = '<div class="modal-panel"><div id="product-modal-content"></div></div>';
document.body.appendChild(modal);

modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.closest('[data-modal-close]')) {
        modal.classList.remove('is-open');
        document.getElementById('product-modal-content').innerHTML = '';
    }
});

document.addEventListener('click', async (event) => {
    const trigger = event.target.closest('[data-product-modal]');
    if (!trigger) return;

    event.preventDefault();
    const url = new URL(trigger.href, window.location.origin);
    url.searchParams.set('modal', '1');

    const response = await fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'text/html',
        },
    });

    const html = await response.text();
    document.getElementById('product-modal-content').innerHTML = html;
    modal.classList.add('is-open');
});
