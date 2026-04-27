@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="section-tag">Administration</p>
            <h1 class="section-title mt-3">Gestion des Produits</h1>
            <p class="section-subtitle">Mise a jour, suppression, promotions, suivi du stock et alertes de seuil.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.produits.create') }}" class="store-btn-primary" data-async-link>Ajouter un produit</a>
            <a href="{{ route('catalogue.index') }}" class="store-btn-secondary" data-async-link>Voir la boutique</a>
        </div>
    </section>

    <div class="mb-6 grid gap-3 md:grid-cols-4">
        <div class="admin-card"><p class="metric-label">Produits</p><p class="metric-value">{{ $produits->total() }}</p></div>
        <div class="admin-card"><p class="metric-label">Alertes stock</p><p class="metric-value text-rose-600">{{ $lowStockCount }}</p></div>
        <div class="admin-card"><p class="metric-label">Categories</p><p class="metric-value">{{ $categories->count() }}</p></div>
        <div class="admin-card"><p class="metric-label">Recherche</p><p class="mt-2 text-sm font-semibold">{{ $search ?: 'Toutes' }}</p></div>
    </div>

    <section class="admin-card mb-6">
        <form method="GET" action="{{ route('admin.produits.index') }}" class="grid gap-3 md:grid-cols-[1fr_auto]" data-async>
            <input type="text" name="q" value="{{ $search }}" class="store-input" placeholder="Rechercher un produit..." />
            <button type="submit" class="store-btn-secondary">Rechercher</button>
        </form>
    </section>

    <section class="space-y-4">
        @foreach ($produits as $produit)
            <article class="admin-card">
                <div class="grid gap-4 lg:grid-cols-[120px_1fr_auto] lg:items-center">
                    <img src="{{ $produit->image }}" alt="{{ $produit->nom }}" class="h-28 w-full rounded-2xl object-cover" />
                    <div>
                        <h2 class="text-lg font-black text-[var(--isipa-admin-ink)]">{{ $produit->nom }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $produit->description }}</p>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-3 py-1">Prix: {{ number_format($produit->prix, 2, ',', ' ') }} $</span>
                            <span class="rounded-full {{ $produit->stock <= 5 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} px-3 py-1">Stock: {{ $produit->stock }}</span>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700">Statut: {{ $produit->statut }}</span>
                            @if ($produit->promotion_percentage)
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">Promo: {{ $produit->promotion_percentage }}%</span>
                            @endif
                        </div>
                    </div>
                    <div class="grid gap-2">
                        <a href="{{ route('admin.produits.edit', $produit) }}" class="store-btn-secondary text-center" data-async-link>Modifier</a>
                        <form method="POST" action="{{ route('admin.produits.destroy', $produit) }}" data-async>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="store-btn-danger w-full">Supprimer</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <div class="mt-8">{{ $produits->links() }}</div>
@endsection
