<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $produits = Produit::where('statut', 'disponible')->orderBy('nom')->get();
        $reclamations = Reclamation::with('produit')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('store.reclamations.index', compact('produits', 'reclamations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        Reclamation::create([
            'user_id' => $request->user()->id,
            'produit_id' => $validated['produit_id'],
            'message' => $validated['message'],
            'statut' => 'en attente',
        ]);

        return back()->with('success', 'Reclamation envoyee avec succes.');
    }
}
