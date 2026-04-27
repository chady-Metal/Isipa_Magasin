<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Notifications\CommandeConfirmeeNotification;
use App\Notifications\CommandeRejeteeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        $commandes = Commande::with(['produits', 'paiement'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $notifications = $request->user()->notifications()->latest()->take(8)->get();
        $totalSpent = $commandes->sum(fn ($commande) => (float) ($commande->paiement->montant ?? 0));

        return view('store.commandes.index', compact('commandes', 'notifications', 'totalSpent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adresse_livraison' => ['required', 'string', 'max:255'],
            'methode_paiement' => ['required', 'string', 'in:mobile-money,carte,virement'],
            'numero_compte' => ['required', 'string', 'max:50'],
        ]);

        $user = $request->user();
        $panier = $user->panier()->with('produits')->first();

        if (! $panier || $panier->produits->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        foreach ($panier->produits as $produit) {
            if ($produit->pivot->quantite > $produit->stock) {
                return back()->with('error', "Stock insuffisant pour {$produit->nom}.");
            }
        }

        DB::transaction(function () use ($validated, $panier, $user) {
            $commande = Commande::create([
                'date_commande' => now()->toDateString(),
                'statut' => Commande::STATUT_EN_ATTENTE,
                'tracking_code' => 'TRK-'.Str::upper(Str::random(10)),
                'user_id' => $user->id,
                'adresse_livraison' => $validated['adresse_livraison'],
                'date_livraison' => now()->addDays(3)->toDateString(),
            ]);

            $montantTotal = 0;

            foreach ($panier->produits as $produit) {
                $quantite = $produit->pivot->quantite;
                $montantTotal += $produit->prixPromo() * $quantite;

                $commande->produits()->attach($produit->id, ['quantite' => $quantite]);
                $produit->decrement('stock', $quantite);
            }

            Paiement::create([
                'montant' => $montantTotal,
                'date_paiement' => now()->toDateString(),
                'methode_paiement' => $validated['methode_paiement'],
                'commande_id' => $commande->id,
                'numero_compte' => $validated['numero_compte'],
                'reference_transaction' => strtoupper('ISIPA-'.uniqid()),
            ]);

            $panier->produits()->detach();
        });

        return redirect()->route('client.commandes.index')->with('success', 'Commande enregistree avec succes.');
    }

    public function cancel(Request $request, Commande $commande)
    {
        if ($commande->user_id !== $request->user()->id) {
            abort(403, 'Vous ne pouvez pas annuler cette commande.');
        }

        if (! $commande->canBeCancelled()) {
            return back()->with('error', 'Seules les commandes en attente peuvent etre annulees.');
        }

        DB::transaction(function () use ($commande) {
            $commande->load('produits');

            foreach ($commande->produits as $produit) {
                $produit->increment('stock', $produit->pivot->quantite);
            }

            $commande->update(['statut' => Commande::STATUT_ANNULEE]);
        });

        return back()->with('success', 'Commande annulee avec succes.');
    }

    public function adminIndex(Request $request)
    {
        $this->ensurePermission($request, 'orders.view');

        $commandes = Commande::with(['user', 'produits', 'paiement', 'processedBy'])
            ->latest()
            ->paginate(12);

        $salesTotal = Paiement::sum('montant');
        $pendingCount = Commande::where('statut', Commande::STATUT_EN_ATTENTE)->count();

        return view('store.admin.commandes.index', compact('commandes', 'salesTotal', 'pendingCount'));
    }

    public function confirm(Request $request, Commande $commande)
    {
        $this->ensurePermission($request, 'orders.manage');

        if (! $commande->canBeManagedByAdmin()) {
            return back()->with('error', 'Cette commande ne peut plus etre modifiee.');
        }

        $commande->update([
            'statut' => Commande::STATUT_CONFIRMEE,
            'rejection_reason' => null,
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
        ]);

        $commande->user?->notify(new CommandeConfirmeeNotification($commande));
        $this->logAdminActivity($request, 'Confirmation commande', 'commande', $commande->id, $commande->tracking_code);

        return back()->with('success', "Commande #{$commande->id} confirmee.");
    }

    public function reject(Request $request, Commande $commande)
    {
        $this->ensurePermission($request, 'orders.manage');

        if (! $commande->canBeManagedByAdmin()) {
            return back()->with('error', 'Cette commande ne peut plus etre modifiee.');
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        DB::transaction(function () use ($commande, $data, $request) {
            $commande->load('produits');

            foreach ($commande->produits as $produit) {
                $produit->increment('stock', $produit->pivot->quantite);
            }

            $commande->update([
                'statut' => Commande::STATUT_REJETEE,
                'rejection_reason' => $data['rejection_reason'],
                'processed_by' => $request->user()->id,
                'processed_at' => now(),
            ]);
        });

        $commande->user?->notify(new CommandeRejeteeNotification($commande, $data['rejection_reason']));
        $this->logAdminActivity($request, 'Rejet commande', 'commande', $commande->id, $data['rejection_reason']);

        return back()->with('success', "Commande #{$commande->id} rejetee.");
    }
}
