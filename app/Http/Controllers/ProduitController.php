<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $categorieId = $request->query('categorie');

        $query = Produit::with('categorie')->where('statut', 'disponible');

        if ($categorieId) {
            $query->where('categorie_id', $categorieId);
        }

        $produits = $query->latest()->paginate(12)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('store.catalogue.index', compact('produits', 'categories', 'categorieId'));
    }

    public function adminIndex()
    {
        $produits = Produit::with('categorie')->latest()->paginate(10);

        return view('store.admin.produits.index', compact('produits'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();

        return view('store.admin.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prix' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'url'],
            'date_fabrication' => ['required', 'date'],
            'statut' => ['required', 'string', 'in:disponible,indisponible'],
            'categorie_id' => ['required', 'exists:categories,id'],
        ]);

        Produit::create($data);

        return redirect()->route('admin.produits.index')->with('success', 'Produit ajoute avec succes.');
    }
}
