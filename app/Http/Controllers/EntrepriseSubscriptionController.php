<?php

namespace App\Http\Controllers;

use App\Models\PlatformAuditLog;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionExpiryService;
use App\Support\CurrentEntreprise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EntrepriseSubscriptionController extends Controller
{
    public function show(Request $request): View
    {
        $entreprise = app(CurrentEntreprise::class)->get();
        app(SubscriptionExpiryService::class)->expireIfDue($entreprise, $request);

        return view('entreprises.subscription-status', [
            'activeEntreprise' => $entreprise->load(['subscriptionPlan', 'requestedSubscriptionPlan']),
            'plans' => SubscriptionPlan::where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function requestPlan(Request $request): RedirectResponse
    {
        $entreprise = app(CurrentEntreprise::class)->get();
        $validated = $request->validate([
            'subscription_plan' => [
                'required',
                'string',
                Rule::exists('subscription_plans', 'slug')->where('is_active', true),
            ],
        ]);

        abort_unless($entreprise->verification_status === 'approved', 403);

        $before = $entreprise->only(['subscription_requested_plan', 'subscription_requested_at']);
        $entreprise->update([
            'subscription_requested_plan' => $validated['subscription_plan'],
            'subscription_requested_at' => now(),
        ]);

        PlatformAuditLog::create([
            'actor_id' => $request->user()->id,
            'entreprise_id' => $entreprise->id,
            'action' => 'subscription.requested',
            'changes' => [
                'before' => $before,
                'after' => $entreprise->only(['subscription_requested_plan', 'subscription_requested_at']),
            ],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Votre demande de forfait a été transmise à l’équipe PharmaGest. Votre accès reste soumis à la date d’échéance affichée.');
    }
}
