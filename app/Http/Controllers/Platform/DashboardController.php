<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\PlatformAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $deadline = $now->copy()->addDays(7);

        $metrics = [
            'companies' => Entreprise::count(),
            'pending' => Entreprise::where('verification_status', 'pending')->count(),
            'active' => Entreprise::where('subscription_status', 'active')->count(),
            'expired' => Entreprise::whereIn('subscription_status', ['expired', 'suspended'])->count(),
            'planRequests' => Entreprise::whereNotNull('subscription_requested_plan')->count(),
            'expiringSoon' => Entreprise::where(function (Builder $query) use ($now, $deadline) {
                $query->where(function (Builder $query) use ($now, $deadline) {
                    $query->where('subscription_status', 'trial')
                        ->whereBetween('trial_ends_at', [$now, $deadline]);
                })->orWhere(function (Builder $query) use ($now, $deadline) {
                    $query->where('subscription_status', 'active')
                        ->whereBetween('subscription_ends_at', [$now, $deadline]);
                });
            })->count(),
        ];

        $recentCompanies = Entreprise::withCount('users')
            ->with(['subscriptionPlan', 'requestedSubscriptionPlan'])
            ->latest()
            ->limit(6)
            ->get();

        $recentActivity = PlatformAuditLog::with(['actor', 'entreprise'])
            ->latest()
            ->limit(8)
            ->get();

        return view('platform.dashboard', compact('metrics', 'recentCompanies', 'recentActivity'));
    }
}
