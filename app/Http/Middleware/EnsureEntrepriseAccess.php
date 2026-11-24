<?php

namespace App\Http\Middleware;

use App\Support\CurrentEntreprise;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEntrepriseAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $entreprise = app(CurrentEntreprise::class)->get();

        if ($entreprise->verification_status !== 'approved') {
            return redirect()->route('entreprise.verification.status');
        }

        if (in_array($entreprise->subscription_status, ['suspended', 'pending'], true)
            || ($entreprise->subscription_status === 'trial'
                && (! $entreprise->trial_ends_at || $entreprise->trial_ends_at->isPast()))
            || ($entreprise->subscription_status === 'active'
                && $entreprise->subscription_ends_at?->isPast())) {
            return redirect()->route('entreprise.subscription.status');
        }

        return $next($request);
    }
}
