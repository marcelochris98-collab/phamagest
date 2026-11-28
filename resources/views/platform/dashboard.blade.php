@extends('layouts.platform')

@section('title', 'Vue d’ensemble')
@section('page_title', 'Vue d’ensemble')

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-sm font-medium text-slate-500">{{ now()->format('d/m/Y') }}</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">Bonjour, {{ auth()->user()->name }}</h2>
        <p class="mt-2 text-sm text-slate-600">Suivez les entreprises, les abonnements et les demandes de votre plateforme.</p>
    </div>
    <a href="{{ route('platform.companies.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
        Gérer les entreprises
        <span aria-hidden="true">→</span>
    </a>
</div>

<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Indicateurs plateforme">
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Entreprises inscrites</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ number_format($metrics['companies']) }}</p>
        <p class="mt-1 text-xs text-slate-500">Espaces entreprise enregistrés</p>
    </article>
    <article class="rounded-2xl border border-amber-200 bg-amber-50/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-amber-900">Demandes en attente</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-amber-950">{{ number_format($metrics['pending']) }}</p>
        <p class="mt-1 text-xs text-amber-900/70">À vérifier avant activation</p>
    </article>
    <article class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-emerald-900">Abonnements actifs</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-emerald-950">{{ number_format($metrics['active']) }}</p>
        <p class="mt-1 text-xs text-emerald-900/70">Entreprises avec un forfait actif</p>
    </article>
    <article class="rounded-2xl border border-violet-200 bg-violet-50/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-violet-900">Demandes de forfait</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-violet-950">{{ number_format($metrics['planRequests']) }}</p>
        <p class="mt-1 text-xs text-violet-900/70">À traiter dans la gestion des entreprises</p>
    </article>
    <article class="rounded-2xl border border-orange-200 bg-orange-50/60 p-5 shadow-sm">
        <p class="text-sm font-medium text-orange-900">Échéance sous 7 jours</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-orange-950">{{ number_format($metrics['expiringSoon']) }}</p>
        <p class="mt-1 text-xs text-orange-900/70">Essais ou abonnements bientôt terminés</p>
    </article>
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Suspendus ou expirés</p>
        <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ number_format($metrics['expired']) }}</p>
        <p class="mt-1 text-xs text-slate-500">Accès entreprise à vérifier ou renouveler</p>
    </article>
</section>

<div class="mt-8 grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
            <div>
                <h2 class="font-semibold text-slate-900">Entreprises récentes</h2>
                <p class="mt-1 text-xs text-slate-500">Derniers espaces créés sur la plateforme</p>
            </div>
            <a href="{{ route('platform.companies.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900">Voir toutes <span aria-hidden="true">→</span></a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Entreprise</th>
                        <th class="px-5 py-3">Forfait</th>
                        <th class="px-5 py-3">Statut</th>
                        <th class="px-5 py-3 text-right">Membres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentCompanies as $entreprise)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-3.5 sm:px-6">
                                <p class="font-semibold text-slate-900">{{ $entreprise->nom }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $entreprise->email ?: 'E-mail non renseigné' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ $entreprise->subscriptionPlan?->name ?? 'Non défini' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-800' => $entreprise->subscription_status === 'active',
                                    'bg-sky-50 text-sky-800' => $entreprise->subscription_status === 'trial',
                                    'bg-amber-50 text-amber-900' => in_array($entreprise->subscription_status, ['pending', 'expired', 'suspended']),
                                ])>{{ ucfirst($entreprise->subscription_status) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right font-medium text-slate-700">{{ $entreprise->users_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">Aucune entreprise inscrite.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h2 class="font-semibold text-slate-900">Activité récente</h2>
            <p class="mt-1 text-xs text-slate-500">Dernières actions de gestion plateforme</p>
        </div>
        <div class="p-5 sm:px-6">
            @forelse ($recentActivity as $activity)
                <div class="relative flex gap-3 pb-5 last:pb-0">
                    @unless ($loop->last)
                        <span class="absolute bottom-0 left-[13px] top-7 w-px bg-slate-200"></span>
                    @endunless
                    <span class="relative z-10 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-800">{{ str($activity->action)->replace('.', ' ')->replace('_', ' ')->ucfirst() }}</p>
                        <p class="mt-0.5 text-xs leading-5 text-slate-500">
                            {{ $activity->entreprise?->nom ?? 'Gestion des forfaits' }}
                            · {{ $activity->actor?->email ?? 'Compte supprimé' }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <p class="text-sm font-medium text-slate-700">Aucune activité enregistrée</p>
                    <p class="mt-1 text-xs text-slate-500">Les actions de la plateforme apparaîtront ici.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<section class="mt-6 grid gap-4 sm:grid-cols-2">
    <a href="{{ route('platform.companies.index') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
        <span>
            <span class="block text-sm font-semibold text-slate-900">Gérer les entreprises</span>
            <span class="mt-1 block text-xs text-slate-500">Vérification, abonnements et demandes</span>
        </span>
        <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-emerald-700" aria-hidden="true">→</span>
    </a>
    <a href="{{ route('platform.plans.index') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
        <span>
            <span class="block text-sm font-semibold text-slate-900">Configurer les forfaits</span>
            <span class="mt-1 block text-xs text-slate-500">Prix, limites et fonctionnalités incluses</span>
        </span>
        <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-emerald-700" aria-hidden="true">→</span>
    </a>
</section>
@endsection