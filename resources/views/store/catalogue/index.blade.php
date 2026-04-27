@extends('store.layouts.app')

@section('content')
    <section class="hero-shell reveal-up mb-8 overflow-hidden rounded-[2rem] p-8 text-white shadow-2xl sm:p-10">
        <div class="relative z-10">
            <p class="section-tag !border-white/40 !bg-white/20 !text-white">Catalogue</p>
            <h1 class="mt-3 text-3xl font-black sm:text-4xl">Recherche, filtre et details produits</h1>
            <p class="mt-3 max-w-2xl text-sm text-blue-100 sm:text-base">
                Chaque produit peut etre consulte en detail, ajoute au panier ou aux favoris, et commente par les clients connectes.
            </p>
        </div>
        <div class="hero-orb hero-orb-a"></div>
        <div class="hero-orb hero-orb-b"></div>
    </section>

    <section class="glass-panel mb-8 p-4 sm:p-5">
        <form method="GET" action="{{ route('catalogue.index') }}" class="grid gap-3 lg:grid-cols-[1.2fr_0.8fr_0.7fr_0.7fr_auto_auto]" data-async>
            <input type="text" name="q" value="{{ $search }}" class="store-input" placeholder="Rechercher un produit..." />
            <select name="categorie" class="store-select">
                <option value="">Toutes les categories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected((string) $categorieId === (string) $categorie->id)>{{ $categorie->nom }}</option>
                @endforeach
            </select>
            <input type="number" step="0.01" min="0" name="prix_max" value="{{ $prixMax }}" class="store-input" placeholder="Prix max" />
            <label class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                <input type="checkbox" name="promotion" value="1" @checked($promotion) />
                Promotions
            </label>
            <button type="submit" class="store-btn-primary">Filtrer</button>
            <a href="{{ route('catalogue.index') }}" class="store-btn-secondary text-center" data-async-link>Reinitialiser</a>
        </form>
    </section>

    <section class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
        <div class="metric-card">
            <p class="metric-label">Produits visibles</p>
            <p class="metric-value">{{ $produits->total() }}</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">Categories</p>
            <p class="metric-value">{{ $categories->count() }}</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">Recherche</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">{{ $search ?: 'Aucune' }}</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">Promotions</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">{{ $promotion ? 'Oui' : 'Toutes' }}</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">Acces</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">Visiteur + Client</p>
        </div>
    </section>

    @auth
        @if (auth()->user()->isClient() && auth()->user()->recherches()->exists())
            <section class="mb-8 store-card store-card-rich">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-black text-[var(--isipa-ink)]">Historique des recherches</h2>
                    <span class="text-xs text-slate-500">5 dernieres recherches</span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (auth()->user()->recherches()->latest()->take(5)->get() as $item)
                        <span class="rounded-full bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-primary)]">{{ $item->terme_recherche }}</span>
                    @endforeach
                </div>
            </section>
        @endif
    @endauth

    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($produits as $produit)
            <article class="store-card store-card-rich flex flex-col">
                <div class="mb-4 h-48 overflow-hidden rounded-xl bg-slate-100">
                    <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full w-full object-cover transition duration-300 hover:scale-105" />
                </div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="mb-2 inline-flex w-fit rounded-full bg-[var(--isipa-tertiary)] px-3 py-1 text-xs font-semibold text-[var(--isipa-ink)]">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                        <h2 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
                    </div>
                    <a href="{{ route('catalogue.show', $produit) }}" data-product-modal class="store-btn-secondary !px-3 !py-2">Infos</a>
                </div>
                <p class="mt-2 flex-1 whitespace-pre-line text-sm text-slate-600">{{ $produit->description ?: 'Produit informatique de qualite propose par ISIPA.' }}</p>

                @if ($produit->promotion_percentage)
                    <div class="mt-3 flex items-center gap-2">
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Promotion {{ rtrim(rtrim(number_format($produit->promotion_percentage, 2, ',', ' '), '0'), ',') }}%</span>
                        <span class="text-xs text-slate-500">{{ $produit->promotion_title }}</span>
                    </div>
                @endif

                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Prix</p>
                        <p class="text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</p>
                        @if ($produit->promotion_percentage)
                            <p class="text-sm text-slate-400 line-through">{{ number_format($produit->prix, 2, ',', ' ') }} $</p>
                        @endif
                    </div>
                    <p class="text-xs font-semibold {{ $produit->stock <= 5 ? 'text-rose-600' : 'text-slate-500' }}">Stock: {{ $produit->stock }}</p>
                </div>

                @auth
                    @if (auth()->user()->isClient())
                        <div class="mt-4 grid gap-2">
                            <form method="POST" action="{{ route('client.panier.add', $produit) }}" class="flex items-center gap-2" data-async>
                                @csrf
                                <input type="number" name="quantite" min="1" max="{{ $produit->stock }}" value="1" class="store-input !w-24 !py-2" />
                                <button type="submit" class="store-btn-primary flex-1">Ajouter au panier</button>
                            </form>
                            <form method="POST" action="{{ route('client.favoris.toggle', $produit) }}" data-async>
                                @csrf
                                <button type="submit" class="store-btn-secondary w-full">
                                    {{ auth()->user()->favoris()->where('produit_id', $produit->id)->exists() ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="mt-4 rounded-xl bg-[var(--isipa-soft)] p-3 text-xs text-[var(--isipa-ink)]">
                        Connectez-vous pour commander ou enregistrer vos favoris.
                    </div>
                @endauth
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Aucun produit disponible pour le moment.
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $produits->links() }}
    </div>
@endsection
