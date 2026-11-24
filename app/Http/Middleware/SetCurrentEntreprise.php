<?php

namespace App\Http\Middleware;

use App\Support\CurrentEntreprise;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentEntreprise
{
    public function handle(Request $request, Closure $next): Response
    {
        $entreprises = $request->user()
            ->entreprises()
            ->orderBy('nom')
            ->get();

        abort_if($entreprises->isEmpty(), 403, 'Votre compte n’est associé à aucune entreprise.');

        $selectedId = $request->session()->get('active_entreprise_id');
        $entreprise = $entreprises->first(
            fn ($candidate) => (string) $candidate->getKey() === (string) $selectedId
        );

        if (! $entreprise && $entreprises->count() === 1) {
            $entreprise = $entreprises->first();
            $request->session()->put('active_entreprise_id', $entreprise->getKey());
        }

        if (! $entreprise) {
            $request->session()->forget('active_entreprise_id');

            return redirect()->route('entreprise.choose');
        }

        app(CurrentEntreprise::class)->set($entreprise);
        $request->attributes->set('currentEntreprise', $entreprise);
        View::share('activeEntreprise', $entreprise);
        View::share('availableEntreprises', $entreprises);

        return $next($request);
    }
}
