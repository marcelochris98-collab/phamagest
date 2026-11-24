<?php

use App\Http\Middleware\EnsureEntrepriseAccess;
use App\Http\Middleware\EnsurePlatformAdmin;
use App\Http\Middleware\SetCurrentEntreprise;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            Route::middleware('web')
                ->group(base_path('routes/platform.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'entreprise' => SetCurrentEntreprise::class,
            'entreprise.access' => EnsureEntrepriseAccess::class,
            'platform.admin' => EnsurePlatformAdmin::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
        $middleware->prependToPriorityList(
            SubstituteBindings::class,
            SetCurrentEntreprise::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
