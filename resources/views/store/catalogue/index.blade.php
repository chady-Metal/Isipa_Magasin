@extends('store.layouts.app')

@section('content')
    <section class="hero-shell reveal-up mb-8 overflow-hidden rounded-[2rem] p-8 text-white shadow-2xl sm:p-10">
        <div class="relative z-10">
            <p class="section-tag !bg-white/20 !text-white !border-white/40">Catalogue</p>
            <h1 class="mt-3 text-3xl font-black sm:text-4xl">Nos Produits Informatiques</h1>
            <p class="mt-3 max-w-2xl text-sm text-blue-100 sm:text-base">
                Decouvrez des produits fiables et performants. Les visiteurs consultent librement et les clients peuvent commander en quelques clics.
            </p>
        </div>
        <div class="hero-orb hero-orb-a"></div>
        <div class="hero-orb hero-orb-b"></div>
    </section>

    <section class="glass-panel reveal-up reveal-delay-1 mb-8 p-4 sm:p-5">
        <form method="GET" action="{{ route('catalogue.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center">
            <select name="categorie" class="store-select md:max-w-xs">
                <option value="">Toutes les categories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected((string) $categorieId === (string) $categorie->id)>{{ $categorie->nom }}</option>
                @endforeach
            </select>
            <button type="submit" class="store-btn-primary">Filtrer</button>
            <a href="{{ route('catalogue.index') }}" class="store-btn-secondary text-center">Reinitialiser</a>
        </form>
    </section>

    <section class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="metric-card reveal-up reveal-delay-1">
            <p class="metric-label">Produits visibles</p>
            <p class="metric-value">{{ $produits->total() }}</p>
        </div>
        <div class="metric-card reveal-up reveal-delay-1">
            <p class="metric-label">Categories</p>
            <p class="metric-value">{{ $categories->count() }}</p>
        </div>
        <div class="metric-card reveal-up reveal-delay-2">
            <p class="metric-label">Statut</p>
            <p class="mt-2 text-sm font-semibold text-slate-700"><span class="status-dot status-dot-success mr-2"></span>Disponibles</p>
        </div>
        <div class="metric-card reveal-up reveal-delay-2">
            <p class="metric-label">Acces</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">Visiteur + Client</p>
        </div>
    </section>

    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($produits as $produit)
            <article class="store-card store-card-rich reveal-up reveal-delay-3 flex flex-col">
                <div class="mb-4 h-48 overflow-hidden rounded-xl bg-slate-100">
                    <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full w-full object-cover transition duration-300 hover:scale-105" />
                </div>
                <p class="mb-2 inline-flex w-fit rounded-full bg-[var(--isipa-tertiary)] px-3 py-1 text-xs font-semibold text-[var(--isipa-ink)]">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                <h2 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
                <p class="mt-2 flex-1 whitespace-pre-line text-sm text-slate-600">{{ $produit->description ?: 'Produit informatique de qualite propose par ISIPA.' }}</p>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Prix</p>
                        <p class="text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prix, 2, ',', ' ') }} $</p>
                    </div>
                    <p class="text-xs font-semibold text-slate-500">Stock: {{ $produit->stock }}</p>
                </div>

                @auth
                    @if (strtolower(optional(auth()->user()->role)->nom ?? '') === 'client')
                        <form method="POST" action="{{ route('client.panier.add', $produit) }}" class="mt-4 flex items-center gap-2">
                            @csrf
                            <input type="number" name="quantite" min="1" max="{{ $produit->stock }}" value="1" class="store-input !w-24 !py-2" />
                            <button type="submit" class="store-btn-primary flex-1">Ajouter au panier</button>
                        </form>
                    @endif
                @else
                    <div class="mt-4 rounded-xl bg-[var(--isipa-soft)] p-3 text-xs text-[var(--isipa-ink)]">
                        Connectez-vous pour commander.
                        <a href="{{ route('register') }}" class="font-bold text-[var(--isipa-primary)] underline">Creer un compte</a>
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
