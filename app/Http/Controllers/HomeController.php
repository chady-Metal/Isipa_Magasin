<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $produitsVedettes = Produit::with(['categorie', 'avis'])
            ->where('statut', 'disponible')
            ->latest()
            ->take(6)
            ->get();

        $categories = Categorie::withCount('produits')
            ->orderBy('nom')
            ->take(4)
            ->get();

        $promotions = Produit::with('categorie')
            ->whereNotNull('promotion_percentage')
            ->where('statut', 'disponible')
            ->latest()
            ->take(4)
            ->get();

        return view('store.home', compact('produitsVedettes', 'categories', 'promotions'));
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        SupportMessage::create($data);

        return back()->with('success', 'Votre message a ete envoye au service client.');
    }
}
