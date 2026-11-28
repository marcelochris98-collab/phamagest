<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAuditLog;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        return view('platform.plans.index', [
            'plans' => SubscriptionPlan::orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'price_fcfa' => ['nullable', 'integer', 'min:0'],
            'user_limit' => ['nullable', 'integer', 'min:1'],
            'product_limit' => ['nullable', 'integer', 'min:1'],
            'kpis_enabled' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $before = $plan->only(['name', 'price_fcfa', 'user_limit', 'product_limit', 'kpis_enabled', 'is_active']);
        $plan->update([
            ...$validated,
            'kpis_enabled' => $request->boolean('kpis_enabled'),
            'is_active' => $request->boolean('is_active'),
        ]);

        PlatformAuditLog::create([
            'actor_id' => $request->user()->id,
            'action' => 'subscription_plan.updated',
            'changes' => [
                'plan' => $plan->slug,
                'before' => $before,
                'after' => $plan->only(['name', 'price_fcfa', 'user_limit', 'product_limit', 'kpis_enabled', 'is_active']),
            ],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Le forfait {$plan->name} a été mis à jour.");
    }
}
