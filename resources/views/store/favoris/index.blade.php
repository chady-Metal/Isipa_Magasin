@extends('store.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Espace Client</p>
        <h1 class="section-title mt-3">Mes Favoris</h1>
        <p class="section-subtitle">Retrouvez rapidement les produits que vous souhaitez surveiller ou acheter plus tard.</p>
    </section>

    <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($favoris as $produit)
            <article class="store-card store-card-rich">
                <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-48 w-full rounded-xl object-cover" />
                <h2 class="mt-4 text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                <p class="mt-3 text-xl font-black text-[var(--isipa-primary)]">{{ number_format($produit->prixPromo(), 2, ',', ' ') }} $</p>
                <div class="mt-4 grid gap-2">
                    <a href="{{ route('catalogue.show', $produit) }}" data-product-modal class="store-btn-secondary text-center">Voir les details</a>
                    <form method="POST" action="{{ route('client.favoris.toggle', $produit) }}" data-async>
                        @csrf
                        <button type="submit" class="store-btn-danger w-full">Retirer des favoris</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Aucun favori enregistre pour le moment.
            </div>
        @endforelse
    </section>
@endsection
