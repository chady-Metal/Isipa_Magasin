<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function index(Request $request)
    {
        $panier = $request->user()->panier()->firstOrCreate(['user_id' => $request->user()->id]);
        $panier->load('produits');

        $total = $panier->produits->sum(fn ($produit) => $produit->prixPromo() * $produit->pivot->quantite);

        return view('store.panier.index', compact('panier', 'total'));
    }

    public function add(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        if ($produit->stock < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant pour ce produit.');
        }

        $panier = $request->user()->panier()->firstOrCreate(['user_id' => $request->user()->id]);
        $existing = $panier->produits()->where('produit_id', $produit->id)->first();
        $nouvelleQuantite = $validated['quantite'] + ($existing?->pivot->quantite ?? 0);

        if ($nouvelleQuantite > $produit->stock) {
            return back()->with('error', 'La quantite demandee depasse le stock disponible.');
        }

        $panier->produits()->syncWithoutDetaching([
            $produit->id => ['quantite' => $nouvelleQuantite],
        ]);

        return back()->with('success', 'Produit ajoute au panier.');
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantite'] > $produit->stock) {
            return back()->with('error', 'La quantite demandee depasse le stock disponible.');
        }

        $panier = $request->user()->panier;

        if (! $panier) {
            return back()->with('error', 'Aucun panier trouve.');
        }

        $panier->produits()->updateExistingPivot($produit->id, ['quantite' => $validated['quantite']]);

        return back()->with('success', 'Panier mis a jour.');
    }

    public function remove(Request $request, Produit $produit)
    {
        $panier = $request->user()->panier;

        if (! $panier) {
            return back()->with('error', 'Aucun panier trouve.');
        }

        $panier->produits()->detach($produit->id);

        return back()->with('success', 'Produit retire du panier.');
    }
}
