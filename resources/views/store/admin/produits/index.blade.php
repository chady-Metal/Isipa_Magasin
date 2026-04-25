@extends('store.layouts.app')

@section('content')
    <section class="mb-6 reveal-up">
        <p class="section-tag">Administration</p>
        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="section-title">Gestion des Produits</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('catalogue.index') }}" class="store-btn-secondary text-center">Voir la boutique</a>
                <a href="{{ route('admin.commandes.index') }}" class="store-btn-secondary text-center">Voir commandes</a>
                <a href="{{ route('admin.produits.create') }}" class="store-btn-primary text-center">Ajouter un produit</a>
            </div>
        </div>
        <p class="section-subtitle">Espace administrateur pour piloter votre catalogue ISIPA Store.</p>
    </section>

    <section class="space-y-4">
        @forelse ($produits as $produit)
            <article class="store-card store-card-rich reveal-up reveal-delay-2">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
                        <p class="text-sm text-slate-500">{{ $produit->categorie->nom ?? 'Sans categorie' }} | {{ number_format($produit->prix, 2, ',', ' ') }} $</p>
                    </div>
                    <div class="text-sm font-semibold text-slate-600">
                        Stock: {{ $produit->stock }} | Statut: {{ ucfirst($produit->statut) }}
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Aucun produit enregistre.
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $produits->links() }}
    </div>
@endsection
