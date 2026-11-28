<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\EntrepriseDocument;
use App\Models\PlatformAuditLog;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'verification_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
            'subscription_status' => ['nullable', Rule::in(['pending', 'trial', 'active', 'suspended', 'expired'])],
            'plan' => ['nullable', 'string', Rule::exists('subscription_plans', 'slug')],
        ]);

        $query = Entreprise::query()
            ->withCount('users')
            ->with(['subscriptionPlan', 'requestedSubscriptionPlan'])
            ->addSelect([
                'last_activity_at' => DB::table('entreprise_user')
                    ->selectRaw('MAX(last_activity_at)')
                    ->whereColumn('entreprise_user.entreprise_id', 'entreprises.id'),
            ])
            ->withCasts(['last_activity_at' => 'datetime']);

        if ($search = $filters['search'] ?? null) {
            $query->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhereHas('users', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($verificationStatus = $filters['verification_status'] ?? null) {
            $query->where('verification_status', $verificationStatus);
        }

        if ($subscriptionStatus = $filters['subscription_status'] ?? null) {
            $query->where('subscription_status', $subscriptionStatus);
        }

        if ($plan = $filters['plan'] ?? null) {
            $query->where('subscription_plan', $plan);
        }

        $entreprises = $query->latest()->paginate(20)->withQueryString();

        return view('platform.companies.index', [
            'entreprises' => $entreprises,
            'plans' => SubscriptionPlan::where('is_active', true)->orderBy('id')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Entreprise $entreprise): View
    {
        $entreprise->loadCount('users');
        $entreprise->load([
            'subscriptionPlan',
            'requestedSubscriptionPlan',
            'documents' => fn ($query) => $query->latest(),
            'users' => fn ($query) => $query->orderBy('name'),
            'platformAuditLogs' => fn ($query) => $query->with('actor')->latest(),
        ]);

        return view('platform.companies.show', [
            'entreprise' => $entreprise,
            'plans' => SubscriptionPlan::where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function approve(Request $request, Entreprise $entreprise): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:2000'],
            'trial_days' => ['required', 'integer', 'min:1', 'max:90'],
        ]);

        DB::transaction(function () use ($request, $entreprise, $validated) {
            $before = $entreprise->only(['verification_status', 'subscription_status', 'trial_ends_at']);
            $entreprise->update([
                'verification_status' => 'approved',
                'verification_note' => $validated['note'] ?? null,
                'verified_at' => now(),
                'verified_by' => $request->user()->id,
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays($validated['trial_days']),
                'subscription_ends_at' => null,
            ]);

            $entreprise->documents()
                ->whereNull('reviewed_at')
                ->update([
                    'reviewed_at' => now(),
                    'reviewed_by' => $request->user()->id,
                    'review_note' => $validated['note'] ?? 'Dossier approuvé.',
                ]);

            $this->audit($request, $entreprise, 'company.approved', [
                'before' => $before,
                'after' => $entreprise->only(['verification_status', 'subscription_status', 'trial_ends_at']),
                'note' => $validated['note'] ?? null,
            ]);
        });

        return back()->with('success', 'Entreprise approuvée et période d’essai activée.');
    }

    public function reject(Request $request, Entreprise $entreprise): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $entreprise, $validated) {
            $before = $entreprise->only(['verification_status', 'subscription_status']);
            $entreprise->update([
                'verification_status' => 'rejected',
                'verification_note' => $validated['note'],
                'verified_at' => null,
                'verified_by' => $request->user()->id,
                'subscription_status' => 'pending',
            ]);

            $entreprise->documents()
                ->whereNull('reviewed_at')
                ->update([
                    'reviewed_at' => now(),
                    'reviewed_by' => $request->user()->id,
                    'review_note' => $validated['note'],
                ]);

            $this->audit($request, $entreprise, 'company.rejected', [
                'before' => $before,
                'after' => $entreprise->only(['verification_status', 'subscription_status']),
                'note' => $validated['note'],
            ]);
        });

        return back()->with('success', 'Dossier refusé avec un motif de correction.');
    }

    public function updateSubscription(Request $request, Entreprise $entreprise): RedirectResponse
    {
        $isTrial = $request->input('subscription_status') === 'trial';
        $isActive = $request->input('subscription_status') === 'active';

        $validated = $request->validate([
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'suspended', 'expired'])],
            'subscription_plan' => ['required', 'string', Rule::exists('subscription_plans', 'slug')],
            'trial_ends_at' => $isTrial
                ? ['required', 'date', 'after:now']
                : ['nullable', 'date'],
            'subscription_ends_at' => $isActive
                ? ['required', 'date', 'after:now']
                : ['nullable', 'date'],
        ]);

        abort_unless($entreprise->verification_status === 'approved', 422, 'Vérifiez l’entreprise avant de gérer son abonnement.');

        $before = $entreprise->only([
            'subscription_status',
            'subscription_plan',
            'trial_ends_at',
            'subscription_ends_at',
            'subscription_requested_plan',
            'subscription_requested_at',
        ]);
        $entreprise->update([
            ...$validated,
            'subscription_requested_plan' => null,
            'subscription_requested_at' => null,
        ]);
        $this->audit($request, $entreprise, 'subscription.updated', [
            'before' => $before,
            'after' => $entreprise->only([
                'subscription_status',
                'subscription_plan',
                'trial_ends_at',
                'subscription_ends_at',
                'subscription_requested_plan',
                'subscription_requested_at',
            ]),
        ]);

        return back()->with('success', 'Abonnement mis à jour.');
    }

    public function downloadDocument(EntrepriseDocument $document): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    private function audit(Request $request, Entreprise $entreprise, string $action, array $changes): void
    {
        PlatformAuditLog::create([
            'actor_id' => $request->user()->id,
            'entreprise_id' => $entreprise->id,
            'action' => $action,
            'changes' => $changes,
            'ip_address' => $request->ip(),
        ]);
    }
}
