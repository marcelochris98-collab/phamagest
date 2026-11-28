<?php

namespace App\Providers;

use App\Support\CurrentEntreprise;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CurrentEntreprise::class);
    }

    public function boot(): void
    {
        View::composer('layouts.partials.sidebar', function ($view) {
            $user = auth()->user();

            $navItems = collect(config('navigation', []))->filter(function (array $item) use ($user) {
                if (! $user) {
                    return false;
                }

                if (isset($item['role']) && ! $user->hasRole($item['role'])) {
                    return false;
                }

                if (isset($item['permission']) && ! $user->can($item['permission'])) {
                    return false;
                }

                if ($item['route'] === 'kpis.index') {
                    $entreprise = request()->attributes->get('currentEntreprise');
                    if ($entreprise && ! $entreprise->subscriptionPlan?->kpis_enabled) {
                        return false;
                    }
                }

                return true;
            });

            $view->with('navItems', $navItems);
        });
    }
}
