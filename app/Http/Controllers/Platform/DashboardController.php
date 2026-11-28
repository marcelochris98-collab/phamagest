<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\PlatformAuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $deadline = $now->copy()->addDays(7);
        $onlineSince = $now->copy()->subMinutes(5);

        $metrics = [
            'companies' => Entreprise::count(),
            'companiesOnline' => Entreprise::whereHas('users', function (Builder $query) use ($onlineSince) {
                $query->where('entreprise_user.last_activity_at', '>=', $onlineSince);
            })->count(),
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
            ->addSelect([
                'last_activity_at' => DB::table('entreprise_user')
                    ->selectRaw('MAX(last_activity_at)')
                    ->whereColumn('entreprise_user.entreprise_id', 'entreprises.id'),
            ])
            ->withCasts(['last_activity_at' => 'datetime'])
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
