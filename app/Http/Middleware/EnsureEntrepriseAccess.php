<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionExpiryService;
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

        $hasExpired = app(SubscriptionExpiryService::class)->expireIfDue($entreprise, $request);

        if ($hasExpired || in_array($entreprise->subscription_status, ['suspended', 'pending', 'expired'], true)) {
            return redirect()->route('entreprise.subscription.status');
        }

        return $next($request);
    }
}
