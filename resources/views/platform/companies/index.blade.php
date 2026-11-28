@extends('layouts.platform')

@section('title', 'Entreprises')
@section('page_title', 'Entreprises')

@section('content')
@php
    $verificationLabels = ['pending' => 'À vérifier', 'approved' => 'Vérifiée', 'rejected' => 'À corriger'];
    $subscriptionLabels = [
        'pending' => 'En attente',
        'trial' => 'Essai',
        'active' => 'Actif',
        'suspended' => 'Suspendu',
        'expired' => 'Expiré',
    ];
@endphp

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-sm text-slate-500">Administration de la plateforme</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950">Entreprises</h2>
        <p class="mt-2 text-sm text-slate-600">Recherchez les espaces, vérifiez leurs forfaits et suivez leur activité récente.</p>
    </div>
    <a href="{{ route('platform.plans.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-emerald-200 hover:text-emerald-800">
        Gérer les forfaits <span aria-hidden="true">→</span>
    </a>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5" aria-label="Filtres entreprises">
    <form method="GET" action="{{ route('platform.companies.index') }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(16rem,2fr)_1fr_1fr_1fr_auto_auto]">
        <label class="sr-only" for="company-search">Rechercher une entreprise</label>
        <input id="company-search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Entreprise, contact, e-mail ou téléphone" class="min-w-0 rounded-xl border-slate-200 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500">

        <label class="sr-only" for="verification-status">Statut de vérification</label>
        <select id="verification-status" name="verification_status" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Toutes les vérifications</option>
            @foreach ($verificationLabels as $value => $label)
                <option value="{{ $value }}" @selected(($filters['verification_status'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <label class="sr-only" for="subscription-status">Statut d’abonnement</label>
        <select id="subscription-status" name="subscription_status" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les abonnements</option>
            @foreach ($subscriptionLabels as $value => $label)
                <option value="{{ $value }}" @selected(($filters['subscription_status'] ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <label class="sr-only" for="company-plan">Forfait</label>
        <select id="company-plan" name="plan" class="rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Tous les forfaits</option>
            @foreach ($plans as $plan)
                <option value="{{ $plan->slug }}" @selected(($filters['plan'] ?? '') === $plan->slug)>{{ $plan->name }}</option>
            @endforeach
        </select>

        <button class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">Filtrer</button>
        <a href="{{ route('platform.companies.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Effacer</a>
    </form>
</section>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 sm:px-6">
        <div>
            <h3 class="font-semibold text-slate-900">Liste des entreprises</h3>
            <p class="mt-1 text-xs text-slate-500">{{ $entreprises->total() }} résultat(s) · activité récente mise à jour lors de l’utilisation de l’espace</p>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Active récemment = activité dans les 5 dernières minutes</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th scope="col" class="px-5 py-3.5 sm:px-6">Entreprise</th>
                    <th scope="col" class="px-5 py-3.5">Vérification</th>
                    <th scope="col" class="px-5 py-3.5">Forfait</th>
                    <th scope="col" class="px-5 py-3.5">Abonnement</th>
                    <th scope="col" class="px-5 py-3.5">Utilisateurs</th>
                    <th scope="col" class="px-5 py-3.5">Activité</th>
                    <th scope="col" class="px-5 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($entreprises as $entreprise)
                    @php
                        $isRecentlyActive = $entreprise->last_activity_at?->isAfter(now()->subMinutes(5)) ?? false;
                    @endphp
                    <tr class="transition hover:bg-slate-50/70">
                        <td class="min-w-64 px-5 py-4 sm:px-6">
                            <a href="{{ route('platform.companies.show', $entreprise) }}" class="font-semibold text-slate-900 hover:text-emerald-800">{{ $entreprise->nom }}</a>
                            <p class="mt-1 text-xs text-slate-500">{{ $entreprise->email ?: 'E-mail non renseigné' }}</p>
                            @if ($entreprise->telephone)
                                <p class="mt-0.5 text-xs text-slate-400">{{ $entreprise->telephone }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                'bg-amber-50 text-amber-800' => $entreprise->verification_status === 'pending',
                                'bg-emerald-50 text-emerald-800' => $entreprise->verification_status === 'approved',
                                'bg-rose-50 text-rose-800' => $entreprise->verification_status === 'rejected',
                            ])>{{ $verificationLabels[$entreprise->verification_status] ?? $entreprise->verification_status }}</span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                            <p class="font-medium">{{ $entreprise->subscriptionPlan?->name ?? 'Non défini' }}</p>
                            @if ($entreprise->subscription_requested_plan)
                                <p class="mt-1 text-xs text-sky-700">Demande : {{ $entreprise->requestedSubscriptionPlan?->name ?? $entreprise->subscription_requested_plan }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                'bg-emerald-50 text-emerald-800' => $entreprise->subscription_status === 'active',
                                'bg-sky-50 text-sky-800' => $entreprise->subscription_status === 'trial',
                                'bg-amber-50 text-amber-900' => in_array($entreprise->subscription_status, ['pending', 'expired']),
                                'bg-slate-100 text-slate-700' => $entreprise->subscription_status === 'suspended',
                            ])>{{ $subscriptionLabels[$entreprise->subscription_status] ?? $entreprise->subscription_status }}</span>
                            @if ($entreprise->subscription_status === 'trial' && $entreprise->trial_ends_at)
                                <p class="mt-1 text-xs text-slate-500">Fin le {{ $entreprise->trial_ends_at->format('d/m/Y') }}</p>
                            @elseif ($entreprise->subscription_status === 'active' && $entreprise->subscription_ends_at)
                                <p class="mt-1 text-xs text-slate-500">Fin le {{ $entreprise->subscription_ends_at->format('d/m/Y') }}</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-700">{{ $entreprise->users_count }}</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if ($isRecentlyActive)
                                <span class="inline-flex items-center gap-2 font-medium text-emerald-800"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Active récemment</span>
                                <p class="mt-1 pl-4 text-xs text-slate-500">{{ $entreprise->last_activity_at->diffForHumans() }}</p>
                            @elseif ($entreprise->last_activity_at)
                                <span class="font-medium text-slate-600">Inactive</span>
                                <p class="mt-1 text-xs text-slate-500">{{ $entreprise->last_activity_at->diffForHumans() }}</p>
                            @else
                                <span class="text-slate-400">Jamais active</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('platform.companies.show', $entreprise) }}" class="inline-flex rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-800">Détails</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center">
                            <p class="font-semibold text-slate-800">Aucune entreprise trouvée</p>
                            <p class="mt-1 text-sm text-slate-500">Modifiez les filtres ou vérifiez l’orthographe de votre recherche.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($entreprises->hasPages())
        <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $entreprises->links() }}</div>
    @endif
</section>
@endsection
