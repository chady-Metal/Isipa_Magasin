<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use App\Models\AdminMessage;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Reclamation;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $commandes = Commande::with('user')->latest()->take(6)->get();
        $stockAlerts = Produit::with('categorie')->where('stock', '<=', 5)->orderBy('stock')->get();
        $recentActivities = AdminActivityLog::with('admin')->latest()->take(10)->get();
        $recentMessages = AdminMessage::with(['sender', 'recipient'])->latest()->take(6)->get();
        $supportMessages = SupportMessage::latest()->take(6)->get();

        $stats = [
            'products' => Produit::count(),
            'orders' => Commande::count(),
            'pending_orders' => Commande::where('statut', Commande::STATUT_EN_ATTENTE)->count(),
            'claims' => Reclamation::count(),
            'sales_total' => DB::table('paiements')->sum('montant'),
            'clients' => User::whereHas('role', fn ($query) => $query->where('nom', 'Client'))->count(),
            'admins' => User::whereHas('role', fn ($query) => $query->whereIn('nom', ['Super Administrateur', 'Administrateur', 'Gerant']))->count(),
        ];

        return view('store.admin.dashboard', compact('stats', 'commandes', 'stockAlerts', 'recentActivities', 'recentMessages', 'supportMessages'));
    }
}
