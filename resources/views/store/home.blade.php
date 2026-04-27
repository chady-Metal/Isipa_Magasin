@extends('store.layouts.app')

@section('content')
    <section class="hero-shell reveal-up mb-10 overflow-hidden rounded-[2rem] p-8 text-white shadow-2xl sm:p-12">
        <div class="relative z-10 max-w-3xl">
            <p class="mb-3 inline-flex rounded-full bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em]">ISIPA Informatique</p>
            <h1 class="text-3xl font-black leading-tight sm:text-5xl">La vitrine digitale des produits informatiques de l'ISIPA</h1>
            <p class="mt-5 max-w-2xl text-sm text-blue-100 sm:text-base">
                Explorez notre catalogue, creez votre compte client, ajoutez vos articles au panier et commandez rapidement avec paiement integre.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('catalogue.index') }}" class="store-btn-light">Voir le catalogue</a>
                @guest
                    <a href="{{ route('register') }}" class="store-btn-secondary-alt">Creer un compte</a>
                @endguest
            </div>
        </div>
        <div class="hero-orb hero-orb-a"></div>
        <div class="hero-orb hero-orb-b"></div>
    </section>

    <section class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($categories as $categorie)
            <article class="store-tile reveal-up reveal-delay-1">
                @if ($categorie->image)
                    <div class="mb-3 h-24 overflow-hidden rounded-xl bg-slate-100">
                        <img src="{{ $categorie->image }}" alt="{{ $categorie->nom }}" class="h-full w-full object-cover" />
                    </div>
                @endif
                <h2 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $categorie->nom }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $categorie->description ?: 'Categorie informatique ISIPA' }}</p>
                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-[var(--isipa-primary)]">{{ $categorie->produits_count }} produits</p>
            </article>
        @endforeach
    </section>

    <section class="mb-4 flex items-center justify-between reveal-up reveal-delay-2">
        <h2 class="text-2xl font-black text-[var(--isipa-ink)]">Produits Vedettes</h2>
        <a href="{{ route('catalogue.index') }}" class="store-btn-secondary">Tout voir</a>
    </section>

    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($produitsVedettes as $produit)
            <article class="store-card store-card-rich reveal-up reveal-delay-3">
                <div class="mb-4 h-48 overflow-hidden rounded-xl bg-slate-100">
                    <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full w-full object-cover" />
                </div>
                <p class="mb-2 inline-flex w-fit rounded-full bg-[var(--isipa-tertiary)] px-3 py-1 text-xs font-semibold text-[var(--isipa-ink)]">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                <h3 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h3>
                <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $produit->description ?: 'Un produit fiable pour vos usages academiques et professionnels.' }}</p>
                <p class="mt-4 text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prix, 2, ',', ' ') }} $</p>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Aucun produit mis en avant pour le moment.
            </div>
        @endforelse
    </section>
@endsection
