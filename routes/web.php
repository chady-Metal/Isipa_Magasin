<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ReclamationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [ProduitController::class, 'index'])->name('catalogue.index');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role && strtolower($user->role->nom) === 'administrateur') {
        return redirect()->route('admin.produits.index');
    }

    return redirect()->route('catalogue.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    // Espace client: panier, commande, paiement et reclamations.
    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/{produit}', [PanierController::class, 'add'])->name('panier.add');
    Route::patch('/panier/{produit}', [PanierController::class, 'update'])->name('panier.update');
    Route::delete('/panier/{produit}', [PanierController::class, 'remove'])->name('panier.remove');

    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::patch('/commandes/{commande}/annuler', [CommandeController::class, 'cancel'])->name('commandes.cancel');

    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::post('/reclamations', [ReclamationController::class, 'store'])->name('reclamations.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Espace administrateur: gestion minimale des produits pour le MVP.
    Route::get('/produits', [ProduitController::class, 'adminIndex'])->name('produits.index');
    Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');

    Route::get('/commandes', [CommandeController::class, 'adminIndex'])->name('commandes.index');
    Route::patch('/commandes/{commande}/confirmer', [CommandeController::class, 'confirm'])->name('commandes.confirm');
    Route::patch('/commandes/{commande}/rejeter', [CommandeController::class, 'reject'])->name('commandes.reject');
});

require __DIR__.'/auth.php';
