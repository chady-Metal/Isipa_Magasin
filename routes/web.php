<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ReclamationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact-service-client', [HomeController::class, 'contact'])->name('contact.store');
Route::get('/catalogue', [ProduitController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/produits/{produit}', [ProduitController::class, 'show'])->name('catalogue.show');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
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
    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/{produit}', [PanierController::class, 'add'])->name('panier.add');
    Route::patch('/panier/{produit}', [PanierController::class, 'update'])->name('panier.update');
    Route::delete('/panier/{produit}', [PanierController::class, 'remove'])->name('panier.remove');

    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::patch('/commandes/{commande}/annuler', [CommandeController::class, 'cancel'])->name('commandes.cancel');

    Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/{produit}', [FavoriController::class, 'toggle'])->name('favoris.toggle');

    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::post('/reclamations', [ReclamationController::class, 'store'])->name('reclamations.store');

    Route::post('/produits/{produit}/avis', [AvisController::class, 'storeForProduct'])->name('avis.produit.store');
    Route::post('/services/avis', [AvisController::class, 'storeForService'])->name('avis.service.store');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\AdminSessionController::class, 'create'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\AdminSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/produits', [ProduitController::class, 'adminIndex'])->name('produits.index');
        Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
        Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
        Route::get('/produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
        Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
        Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

        Route::get('/commandes', [CommandeController::class, 'adminIndex'])->name('commandes.index');
        Route::patch('/commandes/{commande}/confirmer', [CommandeController::class, 'confirm'])->name('commandes.confirm');
        Route::patch('/commandes/{commande}/rejeter', [CommandeController::class, 'reject'])->name('commandes.reject');

        Route::get('/reclamations', [ReclamationController::class, 'adminIndex'])->name('reclamations.index');
        Route::patch('/reclamations/{reclamation}/reply', [ReclamationController::class, 'reply'])->name('reclamations.reply');

        Route::get('/clients', [AdminUserController::class, 'clients'])->name('clients.index');
        Route::delete('/clients/{user}', [AdminUserController::class, 'deleteClient'])->name('clients.destroy');

        Route::get('/administrateurs', [AdminUserController::class, 'admins'])->name('admins.index');
        Route::post('/administrateurs', [AdminUserController::class, 'storeAdmin'])->name('admins.store');
        Route::patch('/administrateurs/{user}/revoquer', [AdminUserController::class, 'revoke'])->name('admins.revoke');

        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [AdminMessageController::class, 'store'])->name('messages.store');
    });
});

require __DIR__.'/auth.php';
