@if (request()->boolean('modal'))
    <div class="p-6" data-product-detail>
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="section-tag">Detail produit</p>
                <h2 class="mt-3 text-3xl font-black text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
            </div>
            <button type="button" class="store-btn-secondary" data-modal-close>Fermer</button>
        </div>
        <div class="mt-6 grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
            <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full max-h-[28rem] w-full rounded-[1.5rem] object-cover" />
            <div class="space-y-4">
                <p class="text-sm text-slate-600">{{ $produit->description ?: 'Aucune description detaillee pour ce produit.' }}</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="metric-card"><p class="metric-label">Categorie</p><p class="mt-2 text-sm font-semibold">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p></div>
                    <div class="metric-card"><p class="metric-label">Prix</p><p class="metric-value">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</p></div>
                    <div class="metric-card"><p class="metric-label">Stock</p><p class="mt-2 text-sm font-semibold">{{ $produit->stock }}</p></div>
                    <div class="metric-card"><p class="metric-label">Fabrication</p><p class="mt-2 text-sm font-semibold">{{ optional($produit->date_fabrication)->format('d/m/Y') }}</p></div>
                </div>
                @if ($produit->promotion_percentage)
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <p class="font-semibold">{{ $produit->promotion_title ?: 'Promotion active' }}</p>
                        <p class="mt-1">{{ $produit->promotion_description ?: 'Reduction appliquee sur ce produit.' }}</p>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-black text-[var(--isipa-ink)]">Avis recents</h3>
                    <div class="mt-3 space-y-3">
                        @forelse ($produit->avis->take(4) as $avis)
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                                <p class="font-semibold">{{ $avis->user->name ?? 'Client' }} | {{ $avis->note }}/5</p>
                                <p class="mt-1">{{ $avis->commentaire }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucun avis pour ce produit.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @extends('store.layouts.app')

    @section('content')
        <section class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
            <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full max-h-[32rem] w-full rounded-[2rem] object-cover shadow-xl" />
            <div class="store-card store-card-rich">
                <p class="section-tag">Produit</p>
                <h1 class="mt-4 text-3xl font-black text-[var(--isipa-ink)]">{{ $produit->nom }}</h1>
                <p class="mt-4 text-sm text-slate-600">{{ $produit->description }}</p>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="metric-card"><p class="metric-label">Categorie</p><p class="mt-2 text-sm font-semibold">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p></div>
                    <div class="metric-card"><p class="metric-label">Prix</p><p class="metric-value">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</p></div>
                    <div class="metric-card"><p class="metric-label">Stock</p><p class="mt-2 text-sm font-semibold">{{ $produit->stock }}</p></div>
                    <div class="metric-card"><p class="metric-label">Fabrication</p><p class="mt-2 text-sm font-semibold">{{ optional($produit->date_fabrication)->format('d/m/Y') }}</p></div>
                </div>

                @auth
                    @if (auth()->user()->isClient())
                        <form method="POST" action="{{ route('client.avis.produit.store', $produit) }}" class="mt-6 grid gap-3 md:grid-cols-[0.3fr_1fr_auto]">
                            @csrf
                            <select name="note" class="store-select" required>
                                <option value="">Note</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }}/5</option>
                                @endfor
                            </select>
                            <input type="text" name="commentaire" class="store-input" placeholder="Votre avis sur ce produit" required />
                            <button type="submit" class="store-btn-primary">Publier</button>
                        </form>
                    @endif
                @endauth
            </div>
        </section>

        <section class="mt-8 grid gap-6 lg:grid-cols-2">
            <article class="store-card store-card-rich">
                <h2 class="text-xl font-black text-[var(--isipa-ink)]">Avis clients</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($produit->avis as $avis)
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                            <p class="font-semibold">{{ $avis->user->name ?? 'Client' }} | {{ $avis->note }}/5</p>
                            <p class="mt-1">{{ $avis->commentaire }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucun avis pour ce produit.</p>
                    @endforelse
                </div>
            </article>

            <article class="store-card store-card-rich">
                <h2 class="text-xl font-black text-[var(--isipa-ink)]">Produits similaires</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($suggestions as $suggestion)
                        <a href="{{ route('catalogue.show', $suggestion) }}" class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3" data-async-link>
                            <img src="{{ $suggestion->image }}" alt="{{ $suggestion->nom }}" class="h-16 w-16 rounded-xl object-cover" />
                            <div>
                                <p class="font-semibold text-[var(--isipa-ink)]">{{ $suggestion->nom }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($suggestion->prixPromo(), 2, ',', ' ') }} $</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Aucune suggestion disponible.</p>
                    @endforelse
                </div>
            </article>
        </section>
    @endsection
@endif
