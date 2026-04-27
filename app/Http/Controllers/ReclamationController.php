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
        $reclamations = Reclamation::with(['produit', 'responder'])
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

    public function adminIndex(Request $request)
    {
        $this->ensurePermission($request, 'claims.reply');

        $reclamations = Reclamation::with(['user', 'produit', 'responder'])->latest()->paginate(12);

        return view('store.admin.reclamations.index', compact('reclamations'));
    }

    public function reply(Request $request, Reclamation $reclamation)
    {
        $this->ensurePermission($request, 'claims.reply');

        $data = $request->validate([
            'admin_response' => ['required', 'string', 'min:10'],
            'statut' => ['required', 'string', 'in:en attente,en cours,traitee'],
        ]);

        $reclamation->update([
            'admin_response' => $data['admin_response'],
            'statut' => $data['statut'],
            'responded_by' => $request->user()->id,
            'responded_at' => now(),
        ]);

        $this->logAdminActivity($request, 'Reponse reclamation', 'reclamation', $reclamation->id, $data['statut']);

        return back()->with('success', 'Reclamation traitee avec succes.');
    }
}
