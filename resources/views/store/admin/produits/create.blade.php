@extends('store.layouts.app')

@section('content')
    <section class="mb-6 reveal-up">
        <p class="section-tag">Administration</p>
        <div class="mt-3 flex items-center justify-between">
            <h1 class="section-title">Ajouter un Produit</h1>
            <a href="{{ route('admin.produits.index') }}" class="store-btn-secondary">Retour</a>
        </div>
        <p class="section-subtitle">Completez les informations ci-dessous pour publier un nouveau produit dans le catalogue.</p>
    </section>

    <section class="glass-panel reveal-up reveal-delay-1 max-w-4xl p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.produits.store') }}" class="grid gap-4 sm:grid-cols-2">
            @csrf
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold">Nom</label>
                <input type="text" name="nom" class="store-input" required />
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Prix ($)</label>
                <input type="number" step="0.01" name="prix" class="store-input" required />
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Stock</label>
                <input type="number" name="stock" class="store-input" required />
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold">Description</label>
                <textarea name="description" rows="3" class="store-textarea"></textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold">Image (URL)</label>
                <input type="url" name="image" class="store-input" required />
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Date fabrication</label>
                <input type="date" name="date_fabrication" class="store-input" required />
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Statut</label>
                <select name="statut" class="store-select">
                    <option value="disponible">Disponible</option>
                    <option value="indisponible">Indisponible</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-semibold">Categorie</label>
                <select name="categorie_id" class="store-select" required>
                    <option value="">Choisir une categorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 mt-2">
                <button type="submit" class="store-btn-primary w-full">Enregistrer le produit</button>
            </div>
        </form>
    </section>
@endsection
