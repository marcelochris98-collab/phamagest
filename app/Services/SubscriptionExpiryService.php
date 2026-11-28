<?php

namespace App\Services;

use App\Models\Entreprise;
use App\Models\PlatformAuditLog;
use Illuminate\Http\Request;

class SubscriptionExpiryService
{
    public function expireIfDue(Entreprise $entreprise, ?Request $request = null): bool
    {
        if (! in_array($entreprise->subscription_status, ['trial', 'active'], true)) {
            return false;
        }

        $expiresAt = $entreprise->subscription_status === 'trial'
            ? $entreprise->trial_ends_at
            : $entreprise->subscription_ends_at;

        if (! $expiresAt && $entreprise->subscription_status === 'active') {
            return false;
        }

        if ($expiresAt?->isFuture()) {
            return false;
        }

        $before = $entreprise->only(['subscription_status', 'trial_ends_at', 'subscription_ends_at']);
        $entreprise->update(['subscription_status' => 'expired']);

        PlatformAuditLog::create([
            'entreprise_id' => $entreprise->id,
            'action' => 'subscription.expired',
            'changes' => [
                'before' => $before,
                'after' => $entreprise->only(['subscription_status', 'trial_ends_at', 'subscription_ends_at']),
            ],
            'ip_address' => $request?->ip(),
        ]);

        return true;
    }
}
