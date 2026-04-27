<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Nom</label>
    <input type="text" name="nom" value="{{ old('nom', $produit->nom ?? '') }}" class="store-input" required />
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Prix ($)</label>
    <input type="number" step="0.01" name="prix" value="{{ old('prix', $produit->prix ?? '') }}" class="store-input" required />
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Stock</label>
    <input type="number" name="stock" value="{{ old('stock', $produit->stock ?? '') }}" class="store-input" required />
</div>

<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Description</label>
    <textarea name="description" rows="3" class="store-textarea">{{ old('description', $produit->description ?? '') }}</textarea>
</div>

<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Image (URL)</label>
    <input type="url" name="image" value="{{ old('image', $produit->image ?? '') }}" class="store-input" required />
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Date fabrication</label>
    <input type="date" name="date_fabrication" value="{{ old('date_fabrication', optional($produit->date_fabrication ?? null)->format('Y-m-d')) }}" class="store-input" required />
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Statut</label>
    <select name="statut" class="store-select">
        <option value="disponible" @selected(old('statut', $produit->statut ?? 'disponible') === 'disponible')>Disponible</option>
        <option value="indisponible" @selected(old('statut', $produit->statut ?? '') === 'indisponible')>Indisponible</option>
    </select>
</div>

<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Categorie</label>
    <select name="categorie_id" class="store-select" required>
        <option value="">Choisir une categorie</option>
        @foreach ($categories as $categorie)
            <option value="{{ $categorie->id }}" @selected((string) old('categorie_id', $produit->categorie_id ?? '') === (string) $categorie->id)>{{ $categorie->nom }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Promotion (%)</label>
    <input type="number" step="0.01" min="0" max="100" name="promotion_percentage" value="{{ old('promotion_percentage', $produit->promotion_percentage ?? '') }}" class="store-input" />
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Produit vedette</label>
    <select name="is_featured" class="store-select">
        <option value="0" @selected((string) old('is_featured', $produit->is_featured ?? '0') === '0')>Non</option>
        <option value="1" @selected((string) old('is_featured', $produit->is_featured ?? '0') === '1')>Oui</option>
    </select>
</div>

<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Titre promotion</label>
    <input type="text" name="promotion_title" value="{{ old('promotion_title', $produit->promotion_title ?? '') }}" class="store-input" />
</div>

<div class="sm:col-span-2">
    <label class="mb-1 block text-sm font-semibold">Description promotion</label>
    <textarea name="promotion_description" rows="3" class="store-textarea">{{ old('promotion_description', $produit->promotion_description ?? '') }}</textarea>
</div>
