<?php

use App\Http\Controllers\Platform\CompanyController;
use App\Http\Controllers\Platform\DashboardController;
use App\Http\Controllers\Platform\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'platform.admin'])
    ->prefix('platform')
    ->name('platform.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('companies/{entreprise}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('plans', [SubscriptionPlanController::class, 'index'])->name('plans.index');
        Route::patch('plans/{plan}', [SubscriptionPlanController::class, 'update'])->name('plans.update');
        Route::post('companies/{entreprise}/approve', [CompanyController::class, 'approve'])->name('companies.approve');
        Route::post('companies/{entreprise}/reject', [CompanyController::class, 'reject'])->name('companies.reject');
        Route::patch('companies/{entreprise}/subscription', [CompanyController::class, 'updateSubscription'])->name('companies.subscription');
        Route::get('documents/{document}', [CompanyController::class, 'downloadDocument'])->name('documents.download');
    });
