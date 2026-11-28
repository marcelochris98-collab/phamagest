<?php

namespace App\Http\Middleware;

use App\Support\CurrentEntreprise;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentEntreprise
{
    public function handle(Request $request, Closure $next, string $mode = 'required'): Response
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        View::share('activeEntreprise', null);
        View::share('availableEntreprises', collect());

        $entreprises = $request->user()
            ->entreprises()
            ->orderBy('nom')
            ->get();

        if ($entreprises->isEmpty()) {
            abort_if($mode !== 'optional', 403, 'Votre compte n’est associé à aucune entreprise.');

            return $next($request);
        }

        $selectedId = $request->session()->get('active_entreprise_id');
        $entreprise = $entreprises->first(
            fn ($candidate) => (string) $candidate->getKey() === (string) $selectedId
        );

        if (! $entreprise && $entreprises->count() === 1) {
            $entreprise = $entreprises->first();
            $request->session()->put('active_entreprise_id', $entreprise->getKey());
        }

        if (! $entreprise && $mode !== 'optional') {
            $request->session()->forget('active_entreprise_id');

            return redirect()->route('entreprise.choose');
        }

        if (! $entreprise) {
            return $next($request);
        }

        app(CurrentEntreprise::class)->set($entreprise);
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->getKey());
        $request->attributes->set('currentEntreprise', $entreprise);
        View::share('activeEntreprise', $entreprise);
        View::share('availableEntreprises', $entreprises);

        $lastActivityAt = now();
        DB::table('entreprise_user')
            ->where('entreprise_id', $entreprise->getKey())
            ->where('user_id', $request->user()->getKey())
            ->where(function ($query) use ($lastActivityAt) {
                $query->whereNull('last_activity_at')
                    ->orWhere('last_activity_at', '<=', $lastActivityAt->copy()->subMinute());
            })
            ->update([
                'last_activity_at' => $lastActivityAt,
                'updated_at' => $lastActivityAt,
            ]);

        return $next($request);
    }
}
