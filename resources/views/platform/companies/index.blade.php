@extends('layouts.platform')

@section('title', 'Entreprises')
@section('page_title', 'Entreprises')

@section('content')
<h1 class="mb-6 text-2xl font-semibold">Administration des entreprises</h1>
<div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
    <p class="text-sm text-emerald-950">Gérez les forfaits, les limites et les prix en FCFA.</p>
    <a href="{{ route('platform.plans.index') }}" class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Configurer les forfaits</a>
</div>

<div class="space-y-6">
    @forelse ($entreprises as $entreprise)
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold">{{ $entreprise->nom }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $entreprise->email ?: 'Pas d’e-mail entreprise' }} · {{ $entreprise->users_count }} utilisateur(s)</p>
                    <p class="mt-2 text-sm">Vérification : <strong>{{ $entreprise->verification_status }}</strong> · Abonnement : <strong>{{ $entreprise->subscription_status }}</strong> · Forfait : <strong>{{ $entreprise->subscriptionPlan?->name ?? 'Non défini' }}</strong></p>
                    @if ($entreprise->subscription_requested_plan)
                        <p class="mt-2 rounded-lg bg-sky-50 p-3 text-sm text-sky-900">
                            Demande de forfait : <strong>{{ $entreprise->requestedSubscriptionPlan?->name ?? $entreprise->subscription_requested_plan }}</strong>
                            @if ($entreprise->subscription_requested_at)
                                · le {{ $entreprise->subscription_requested_at->format('d/m/Y à H:i') }}
                            @endif
                        </p>
                    @endif
                    @if ($entreprise->verification_note)
                        <p class="mt-2 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">{{ $entreprise->verification_note }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($entreprise->documents as $document)
                        <a class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-emerald-700 hover:bg-emerald-50" href="{{ route('platform.documents.download', $document) }}">
                            Télécharger {{ $document->type }}
                        </a>
                    @endforeach
                </div>
            </div>
            @if ($entreprise->documents->isEmpty())
                <p class="mt-3 text-sm text-slate-500">Aucun justificatif fourni. L’approbation manuelle reste possible.</p>
            @endif

            @if ($entreprise->verification_status === 'pending')
                <div class="mt-6 grid gap-4 border-t border-slate-100 pt-5 md:grid-cols-2">
                    <form method="POST" action="{{ route('platform.companies.approve', $entreprise) }}" class="space-y-3">
                        @csrf
                        <label class="block text-sm font-medium">Note de validation (facultative)</label>
                        <input name="note" class="w-full rounded-lg border-slate-300 text-sm" maxlength="2000">
                        <label class="block text-sm font-medium">Durée de l’essai (jours)</label>
                        <input name="trial_days" type="number" min="1" max="90" value="14" required class="w-32 rounded-lg border-slate-300 text-sm">
                        <button class="block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Approuver et démarrer l’essai</button>
                    </form>
                    <form method="POST" action="{{ route('platform.companies.reject', $entreprise) }}" class="space-y-3">
                        @csrf
                        <label class="block text-sm font-medium">Motif et corrections demandées</label>
                        <textarea name="note" required maxlength="2000" rows="3" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                        <button class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Demander des corrections</button>
                    </form>
                </div>
            @endif

            @if ($entreprise->verification_status === 'approved')
                <form method="POST" action="{{ route('platform.companies.subscription', $entreprise) }}" class="mt-6 grid gap-3 border-t border-slate-100 pt-5 sm:grid-cols-2 lg:grid-cols-5">
                    @csrf
                    @method('PATCH')
                    <label class="text-sm">Statut
                        <select name="subscription_status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                            @foreach (['trial', 'active', 'suspended', 'expired'] as $status)
                                <option value="{{ $status }}" @selected($entreprise->subscription_status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm">Offre
                        <select name="subscription_plan" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                            @if ($entreprise->subscriptionPlan && ! $entreprise->subscriptionPlan->is_active)
                                <option value="{{ $entreprise->subscriptionPlan->slug }}" selected>{{ $entreprise->subscriptionPlan->name }} — forfait désactivé</option>
                            @endif
                            @if (! $entreprise->subscriptionPlan)
                                <option value="{{ $entreprise->subscription_plan }}" selected>{{ $entreprise->subscription_plan }} — forfait historique</option>
                            @endif
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->slug }}" @selected($entreprise->subscription_plan === $plan->slug)>{{ $plan->name }}{{ $plan->price_fcfa === null ? ' — prix à définir' : ' — '.number_format($plan->price_fcfa, 0, ',', ' ').' FCFA/mois' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm">Fin d’essai
                        <input name="trial_ends_at" type="datetime-local" value="{{ $entreprise->trial_ends_at?->format('Y-m-d\TH:i') }}" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                    </label>
                    <label class="text-sm">Fin d’abonnement
                        <input name="subscription_ends_at" type="datetime-local" value="{{ $entreprise->subscription_ends_at?->format('Y-m-d\TH:i') }}" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                    </label>
                    <button class="self-end rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50">Enregistrer / activer l’offre</button>
                </form>
            @endif

            @if ($entreprise->platformAuditLogs->isNotEmpty())
                <details class="mt-5 border-t border-slate-100 pt-4">
                    <summary class="cursor-pointer text-sm font-semibold text-slate-700">Historique des actions plateforme</summary>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        @foreach ($entreprise->platformAuditLogs as $log)
                            <li>
                                {{ $log->created_at->format('d/m/Y H:i') }} —
                                {{ $log->action }} par {{ $log->actor?->email ?? 'compte supprimé' }}
                                @if (data_get($log->changes, 'note'))
                                    · {{ data_get($log->changes, 'note') }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </article>
    @empty
        <div class="rounded-xl bg-white p-8 text-center text-slate-500">Aucune entreprise inscrite.</div>
    @endforelse

    <div>{{ $entreprises->links() }}</div>
</div>
@endsection
