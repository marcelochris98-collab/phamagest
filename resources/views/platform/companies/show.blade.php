@extends('layouts.platform')

@section('title', $entreprise->nom)
@section('page_title', 'Fiche entreprise')

@section('content')
<div class="mb-6">
    <a href="{{ route('platform.companies.index', request()->query()) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-emerald-800">
        <span aria-hidden="true">←</span> Retour à la liste
    </a>
</div>

<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
    <div class="flex flex-wrap items-start justify-between gap-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Espace entreprise</p>
            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $entreprise->nom }}</h2>
            <p class="mt-2 text-sm text-slate-500">
                {{ $entreprise->email ?: 'E-mail non renseigné' }}
                @if ($entreprise->telephone) · {{ $entreprise->telephone }} @endif
            </p>
            @if ($entreprise->adresse)
                <p class="mt-1 text-sm text-slate-500">{{ $entreprise->adresse }}</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700">{{ ucfirst($entreprise->verification_status) }}</span>
            <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800">{{ ucfirst($entreprise->subscription_status) }}</span>
        </div>
    </div>

    <div class="mt-7 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Forfait</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $entreprise->subscriptionPlan?->name ?? 'Non défini' }}</p>
        </div>
        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Utilisateurs</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $entreprise->users_count }}</p>
        </div>
        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Inscription</p>
            <p class="mt-1 font-semibold text-slate-900">{{ $entreprise->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="rounded-xl bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-500">Fin de période</p>
            <p class="mt-1 font-semibold text-slate-900">
                {{ ($entreprise->subscription_status === 'trial' ? $entreprise->trial_ends_at : $entreprise->subscription_ends_at)?->format('d/m/Y') ?? 'Non définie' }}
            </p>
        </div>
    </div>
</section>

<div class="mt-6 grid gap-6 xl:grid-cols-2">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="font-semibold text-slate-900">Membres et activité</h3>
                <p class="mt-1 text-xs text-slate-500">« Active récemment » signifie une activité observée dans les 5 dernières minutes.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $entreprise->users_count }}</span>
        </div>
        <div class="mt-5 divide-y divide-slate-100">
            @forelse ($entreprise->users as $user)
                @php
                    $lastActivity = $user->pivot->last_activity_at
                        ? \Illuminate\Support\Carbon::parse($user->pivot->last_activity_at)
                        : null;
                @endphp
                <div class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                    </div>
                    <div class="text-right text-xs">
                        @if ($lastActivity?->isAfter(now()->subMinutes(5)))
                            <p class="inline-flex items-center gap-1.5 font-semibold text-emerald-800"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active récemment</p>
                        @elseif ($lastActivity)
                            <p class="font-medium text-slate-600">Inactive</p>
                        @else
                            <p class="text-slate-400">Jamais active</p>
                        @endif
                        @if ($lastActivity)
                            <p class="mt-1 text-slate-400">{{ $lastActivity->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-slate-500">Aucun utilisateur associé.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="font-semibold text-slate-900">Demandes et justificatifs</h3>
        @if ($entreprise->subscription_requested_plan)
            <div class="mt-4 rounded-xl border border-sky-100 bg-sky-50 p-4 text-sm text-sky-950">
                <p class="font-semibold">Demande de forfait : {{ $entreprise->requestedSubscriptionPlan?->name ?? $entreprise->subscription_requested_plan }}</p>
                @if ($entreprise->subscription_requested_at)
                    <p class="mt-1 text-xs text-sky-800">Reçue le {{ $entreprise->subscription_requested_at->format('d/m/Y à H:i') }}</p>
                @endif
            </div>
        @else
            <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Aucune demande de changement de forfait.</p>
        @endif

        @if ($entreprise->verification_note)
            <p class="mt-3 rounded-xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-950">{{ $entreprise->verification_note }}</p>
        @endif

        <div class="mt-4 space-y-2">
            @forelse ($entreprise->documents as $document)
                <a href="{{ route('platform.documents.download', $document) }}" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm transition hover:border-emerald-200 hover:bg-emerald-50/50">
                    <span class="font-medium text-slate-800">{{ ucfirst($document->type) }} · {{ $document->original_name }}</span>
                    <span class="shrink-0 text-xs font-semibold text-emerald-800">Télécharger</span>
                </a>
            @empty
                <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Aucun justificatif fourni. La vérification manuelle reste possible.</p>
            @endforelse
        </div>
    </section>
</div>

@if ($entreprise->verification_status === 'pending')
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="font-semibold text-slate-900">Vérifier la demande d’inscription</h3>
        <div class="mt-5 grid gap-6 md:grid-cols-2">
            <form method="POST" action="{{ route('platform.companies.approve', $entreprise) }}" class="space-y-3">
                @csrf
                <label for="approval-note" class="block text-sm font-medium text-slate-700">Note de validation (facultative)</label>
                <textarea id="approval-note" name="note" maxlength="2000" rows="2" class="block w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('note') }}</textarea>
                <label for="trial-days" class="block text-sm font-medium text-slate-700">Durée de l’essai (jours)</label>
                <input id="trial-days" name="trial_days" type="number" min="1" max="90" value="{{ old('trial_days', 30) }}" required class="block w-32 rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <button class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Approuver et démarrer l’essai</button>
            </form>
            <form method="POST" action="{{ route('platform.companies.reject', $entreprise) }}" class="space-y-3">
                @csrf
                <label for="rejection-note" class="block text-sm font-medium text-slate-700">Motif et corrections demandées</label>
                <textarea id="rejection-note" name="note" required maxlength="2000" rows="3" class="block w-full rounded-xl border-slate-200 text-sm focus:border-rose-500 focus:ring-rose-500">{{ old('note') }}</textarea>
                <button class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">Demander des corrections</button>
            </form>
        </div>
    </section>
@endif

@if ($entreprise->verification_status === 'approved')
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="font-semibold text-slate-900">Gérer l’abonnement</h3>
        <form method="POST" action="{{ route('platform.companies.subscription', $entreprise) }}" class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @csrf
            @method('PATCH')
            <label class="text-sm font-medium text-slate-700">Statut
                <select name="subscription_status" class="mt-1 block w-full rounded-xl border-slate-200 text-sm">
                    @foreach (['trial', 'active', 'suspended', 'expired'] as $status)
                        <option value="{{ $status }}" @selected(old('subscription_status', $entreprise->subscription_status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium text-slate-700">Forfait
                <select name="subscription_plan" required class="mt-1 block w-full rounded-xl border-slate-200 text-sm">
                    @if ($entreprise->subscriptionPlan && ! $entreprise->subscriptionPlan->is_active)
                        <option value="{{ $entreprise->subscriptionPlan->slug }}" @selected(old('subscription_plan', $entreprise->subscription_plan) === $entreprise->subscriptionPlan->slug)>{{ $entreprise->subscriptionPlan->name }} — désactivé</option>
                    @endif
                    @if (! $entreprise->subscriptionPlan)
                        <option value="{{ $entreprise->subscription_plan }}" @selected(old('subscription_plan', $entreprise->subscription_plan) === $entreprise->subscription_plan)>{{ $entreprise->subscription_plan }} — historique</option>
                    @endif
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->slug }}" @selected(old('subscription_plan', $entreprise->subscription_plan) === $plan->slug)>{{ $plan->name }}{{ $plan->price_fcfa === null ? '' : ' — '.number_format($plan->price_fcfa, 0, ',', ' ').' FCFA/mois' }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium text-slate-700">Fin de l’essai
                <input name="trial_ends_at" type="datetime-local" value="{{ old('trial_ends_at', $entreprise->trial_ends_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-xl border-slate-200 text-sm">
            </label>
            <label class="text-sm font-medium text-slate-700">Fin de l’abonnement
                <input name="subscription_ends_at" type="datetime-local" value="{{ old('subscription_ends_at', $entreprise->subscription_ends_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-xl border-slate-200 text-sm">
            </label>
            <button class="self-end rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Enregistrer</button>
        </form>
    </section>
@endif

<section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <h3 class="font-semibold text-slate-900">Historique des actions plateforme</h3>
    <div class="mt-4 divide-y divide-slate-100">
        @forelse ($entreprise->platformAuditLogs as $log)
            <div class="py-3 first:pt-0 last:pb-0">
                <p class="text-sm font-medium text-slate-800">{{ str($log->action)->replace('.', ' ')->replace('_', ' ')->ucfirst() }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $log->created_at->format('d/m/Y à H:i') }} · {{ $log->actor?->email ?? 'Compte supprimé' }}</p>
                @if (data_get($log->changes, 'note'))
                    <p class="mt-1 text-sm text-slate-600">{{ data_get($log->changes, 'note') }}</p>
                @endif
            </div>
        @empty
            <p class="py-5 text-sm text-slate-500">Aucune action enregistrée pour cette entreprise.</p>
        @endforelse
    </div>
</section>
@endsection
