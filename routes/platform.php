<?php

use App\Http\Controllers\Platform\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'platform.admin'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {
        Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('companies/{entreprise}/approve', [CompanyController::class, 'approve'])->name('companies.approve');
        Route::post('companies/{entreprise}/reject', [CompanyController::class, 'reject'])->name('companies.reject');
        Route::patch('companies/{entreprise}/subscription', [CompanyController::class, 'updateSubscription'])->name('companies.subscription');
        Route::get('documents/{document}', [CompanyController::class, 'downloadDocument'])->name('documents.download');
    });
