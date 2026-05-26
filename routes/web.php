<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/kpis', [DashboardController::class, 'kpis'])
        ->middleware('permission:kpis.view')
        ->name('kpis.index');

    Route::resource('fournisseurs', FournisseurController::class)->except(['show']);
    Route::resource('medicaments', MedicamentController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);

    Route::resource('commandes', CommandeController::class);
    Route::post('commandes/{commande}/reception', [CommandeController::class, 'receive'])->name('commandes.receive');
    Route::post('commandes/{commande}/annuler', [CommandeController::class, 'cancel'])->name('commandes.cancel');

    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('stock/alertes', [StockController::class, 'alertes'])->name('stock.alertes');
    Route::get('stock/mouvements', [StockController::class, 'mouvements'])->name('stock.mouvements');

    Route::resource('ventes', VenteController::class);
    Route::post('ventes/{vente}/valider', [VenteController::class, 'validateVente'])->name('ventes.validate');
    Route::get('ventes/{vente}/facture', [VenteController::class, 'invoice'])->name('ventes.invoice');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
