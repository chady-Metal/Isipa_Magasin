<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    public function index(Request $request)
    {
        $favoris = $request->user()->favoris()->with('categorie')->latest('favoris.created_at')->get();

        return view('store.favoris.index', compact('favoris'));
    }

    public function toggle(Request $request, Produit $produit)
    {
        $user = $request->user();
        $exists = $user->favoris()->where('produit_id', $produit->id)->exists();

        if ($exists) {
            $user->favoris()->detach($produit->id);

            return back()->with('success', 'Produit retire des favoris.');
        }

        $user->favoris()->attach($produit->id);

        return back()->with('success', 'Produit ajoute aux favoris.');
    }
}
