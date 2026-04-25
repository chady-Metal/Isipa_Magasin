<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;

class HomeController extends Controller
{
    public function index()
    {
        $produitsVedettes = Produit::with('categorie')
            ->where('statut', 'disponible')
            ->latest()
            ->take(6)
            ->get();

        $categories = Categorie::withCount('produits')
            ->orderBy('nom')
            ->take(4)
            ->get();

        return view('store.home', compact('produitsVedettes', 'categories'));
    }
}
