<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\HistoriqueRecherche;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $categorieId = $request->query('categorie');
        $search = trim((string) $request->query('q'));
        $prixMax = $request->query('prix_max');
        $promotion = $request->boolean('promotion');

        $query = Produit::with(['categorie', 'avis'])
            ->where('statut', 'disponible');

        if ($categorieId) {
            $query->where('categorie_id', $categorieId);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($prixMax) {
            $query->where('prix', '<=', (float) $prixMax);
        }

        if ($promotion) {
            $query->whereNotNull('promotion_percentage');
        }

        $produits = $query->latest()->paginate(12)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        if ($search !== '') {
            HistoriqueRecherche::create([
                'user_id' => $request->user()?->id,
                'terme_recherche' => $search,
                'categorie' => $categorieId ? (string) $categorieId : null,
                'resultats' => $produits->total(),
            ]);
        }

        return view('store.catalogue.index', compact('produits', 'categories', 'categorieId', 'search', 'prixMax', 'promotion'));
    }

    public function show(Produit $produit)
    {
        $produit->load(['categorie', 'avis.user']);

        $suggestions = Produit::with('categorie')
            ->where('id', '!=', $produit->id)
            ->where('categorie_id', $produit->categorie_id)
            ->where('statut', 'disponible')
            ->latest()
            ->take(3)
            ->get();

        return view('store.catalogue.show', compact('produit', 'suggestions'));
    }

    public function adminIndex(Request $request)
    {
        $this->ensurePermission($request, 'products.update');

        $search = trim((string) $request->query('q'));
        $produits = Produit::with('categorie')
            ->when($search !== '', fn ($query) => $query->where('nom', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lowStockCount = Produit::where('stock', '<=', 5)->count();
        $categories = Categorie::orderBy('nom')->get();

        return view('store.admin.produits.index', compact('produits', 'lowStockCount', 'search', 'categories'));
    }

    public function create(Request $request)
    {
        $this->ensurePermission($request, 'products.create');

        $categories = Categorie::orderBy('nom')->get();

        return view('store.admin.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'products.create');

        $data = $this->validateProduct($request);
        Produit::create($data);
        $this->logAdminActivity($request, 'Ajout produit', 'produit', null, $data['nom']);

        return redirect()->route('admin.produits.index')->with('success', 'Produit ajoute avec succes.');
    }

    public function edit(Request $request, Produit $produit)
    {
        $this->ensurePermission($request, 'products.update');

        $categories = Categorie::orderBy('nom')->get();

        return view('store.admin.produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $this->ensurePermission($request, 'products.update');

        $data = $this->validateProduct($request);
        $produit->update($data);
        $this->logAdminActivity($request, 'Mise a jour produit', 'produit', $produit->id, $produit->nom);

        return redirect()->route('admin.produits.index')->with('success', 'Produit mis a jour avec succes.');
    }

    public function destroy(Request $request, Produit $produit)
    {
        $this->ensurePermission($request, 'products.delete');

        $nom = $produit->nom;
        $id = $produit->id;
        $produit->delete();
        $this->logAdminActivity($request, 'Suppression produit', 'produit', $id, $nom);

        return back()->with('success', 'Produit supprime avec succes.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prix' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'url'],
            'date_fabrication' => ['required', 'date'],
            'statut' => ['required', 'string', 'in:disponible,indisponible'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'promotion_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'promotion_title' => ['nullable', 'string', 'max:255'],
            'promotion_description' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }
}
