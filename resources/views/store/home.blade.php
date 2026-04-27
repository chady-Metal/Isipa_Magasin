@extends('store.layouts.app')

@section('content')
    <section class="hero-shell reveal-up mb-10 overflow-hidden rounded-[2rem] p-8 text-white shadow-2xl sm:p-12">
        <div class="relative z-10 max-w-3xl">
            <p class="mb-3 inline-flex rounded-full bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em]">ISIPA Informatique</p>
            <h1 class="text-3xl font-black leading-tight sm:text-5xl">Acheter, suivre, reclamer et echanger sans quitter la boutique</h1>
            <p class="mt-5 max-w-2xl text-sm text-blue-100 sm:text-base">
                Les visiteurs consultent, filtrent, recherchent et contactent le service client. Les clients commandent, suivent leurs achats, ajoutent aux favoris et donnent leurs avis.
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('catalogue.index') }}" class="store-btn-light" data-async-link>Voir le catalogue</a>
                @guest
                    <a href="{{ route('register') }}" class="store-btn-secondary-alt">Creer un compte client</a>
                @endguest
            </div>
        </div>
        <div class="hero-orb hero-orb-a"></div>
        <div class="hero-orb hero-orb-b"></div>
    </section>

    <section class="mb-10 grid gap-4 lg:grid-cols-3">
        <article class="store-card store-card-rich">
            <p class="section-tag">Fonctionnement</p>
            <h2 class="mt-4 text-2xl font-black text-[var(--isipa-ink)]">Comment fonctionne le site</h2>
            <p class="mt-3 text-sm text-slate-600">1. Consultez et filtrez le catalogue. 2. Creez un compte client pour commander. 3. Suivez vos commandes, vos favoris, vos recherches et vos reclamations depuis votre espace.</p>
        </article>
        <article class="store-card store-card-rich">
            <p class="section-tag">Service client</p>
            <h2 class="mt-4 text-2xl font-black text-[var(--isipa-ink)]">Support disponible</h2>
            <p class="mt-3 text-sm text-slate-600">Une question avant achat ? Utilisez le formulaire de contact ci-dessous. Les reclamations clients sont ensuite traitees par les administrateurs et gerants.</p>
        </article>
        <article class="store-card store-card-rich">
            <p class="section-tag">Promotions</p>
            <h2 class="mt-4 text-2xl font-black text-[var(--isipa-ink)]">Offres en cours</h2>
            <p class="mt-3 text-sm text-slate-600">Les promotions publiees par le super administrateur et les administrateurs apparaissent directement ici pour les visiteurs et les clients.</p>
        </article>
    </section>

    @if ($promotions->isNotEmpty())
        <section class="mb-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="section-title !text-2xl">Produits en Promotion</h2>
                <a href="{{ route('catalogue.index', ['promotion' => 1]) }}" class="store-btn-secondary" data-async-link>Voir tout</a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($promotions as $produit)
                    <article class="store-card store-card-rich">
                        <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-40 w-full rounded-xl object-cover" />
                        <p class="mt-4 inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">-{{ rtrim(rtrim(number_format($produit->promotion_percentage, 2, ',', ' '), '0'), ',') }}%</p>
                        <h3 class="mt-3 text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $produit->promotion_title ?: 'Promotion active' }}</p>
                        <div class="mt-4 flex items-end gap-3">
                            <span class="text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</span>
                            <span class="text-sm text-slate-400 line-through">{{ number_format($produit->prix, 2, ',', ' ') }} $</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

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
        <a href="{{ route('catalogue.index') }}" class="store-btn-secondary" data-async-link>Tout voir</a>
    </section>

    <section class="mb-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($produitsVedettes as $produit)
            <article class="store-card store-card-rich reveal-up reveal-delay-3">
                <div class="mb-4 h-48 overflow-hidden rounded-xl bg-slate-100">
                    <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-full w-full object-cover" />
                </div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="mb-2 inline-flex w-fit rounded-full bg-[var(--isipa-tertiary)] px-3 py-1 text-xs font-semibold text-[var(--isipa-ink)]">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                        <h3 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h3>
                    </div>
                    <a href="{{ route('catalogue.show', $produit) }}" data-product-modal class="store-btn-secondary !px-3 !py-2">Infos</a>
                </div>
                <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $produit->description ?: 'Un produit fiable pour vos usages academiques et professionnels.' }}</p>
                <p class="mt-4 text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</p>
            </article>
        @endforeach
    </section>

    <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="store-card store-card-rich">
            <p class="section-tag">A propos</p>
            <h2 class="mt-4 text-2xl font-black text-[var(--isipa-ink)]">Vision globale du site</h2>
            <p class="mt-3 text-sm text-slate-600">ISIPA Store centralise la vitrine des produits informatiques, la commande, le paiement, le suivi de livraison, le support client et une administration multi-profils avec controles de permissions.</p>
        </div>

        <div class="glass-panel p-6">
            <p class="section-tag">Contact</p>
            <h2 class="mt-4 text-2xl font-black text-[var(--isipa-ink)]">Contacter le service client</h2>
            <form method="POST" action="{{ route('contact.store') }}" class="mt-4 space-y-3" data-async>
                @csrf
                <input type="text" name="name" class="store-input" placeholder="Votre nom" required />
                <input type="email" name="email" class="store-input" placeholder="Votre Gmail" required />
                <input type="text" name="subject" class="store-input" placeholder="Objet" required />
                <textarea name="message" rows="4" class="store-textarea" placeholder="Votre message" required></textarea>
                <button type="submit" class="store-btn-primary w-full">Envoyer au service client</button>
            </form>
        </div>
    </section>
@endsection
