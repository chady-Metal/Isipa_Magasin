<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function storeForProduct(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['required', 'string', 'min:5'],
        ]);

        Avis::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'produit_id' => $produit->id,
                'type' => 'produit',
            ],
            $data
        );

        return back()->with('success', 'Votre avis produit a ete enregistre.');
    }

    public function storeForService(Request $request)
    {
        $data = $request->validate([
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['required', 'string', 'min:5'],
        ]);

        Avis::create([
            'user_id' => $request->user()->id,
            'produit_id' => null,
            'type' => 'service',
            'note' => $data['note'],
            'commentaire' => $data['commentaire'],
        ]);

        return back()->with('success', 'Votre avis sur le service a ete envoye.');
    }
}
