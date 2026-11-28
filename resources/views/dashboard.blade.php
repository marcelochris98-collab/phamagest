<x-app-layout>
    <x-slot name="header">Vue d’ensemble</x-slot>
    <x-slot name="subheader">Les repères essentiels de votre activité, réunis au même endroit.</x-slot>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-emerald-950 px-5 py-5 text-white shadow-sm sm:px-7">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-200">Votre espace de travail</p>
            <h2 class="mt-1 text-xl font-semibold">{{ $activeEntreprise->nom ?? 'Bienvenue sur PharmaGest' }}</h2>
            <p class="mt-1 text-sm text-emerald-100/80">Voici les informations utiles pour suivre votre activité aujourd’hui.</p>
        </div>
        <p class="rounded-xl bg-white/10 px-3 py-2 text-sm font-medium">{{ now()->format('d/m/Y') }}</p>
    </div>

    @if (count($stats))
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Indicateurs de l’entreprise">
            @can(\App\Support\Permissions::VENTES_VIEW)
                <article class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Chiffre d’affaires du mois</p>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-800">{{ number_format($stats['revenueThisMonth'], 0, ',', ' ') }} <span class="text-sm font-semibold">FCFA</span></p>
                    <p class="mt-1 text-xs text-slate-500">{{ number_format($stats['salesThisMonth']) }} vente(s) validée(s)</p>
                </article>
            @endcan

            @can(\App\Support\Permissions::MEDICAMENTS_VIEW)
                <a href="{{ route('medicaments.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Produits au catalogue</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($stats['products']) }}</p>
                    <p class="mt-1 text-xs font-medium text-emerald-800">Consulter le catalogue <span aria-hidden="true">→</span></p>
                </a>
            @endcan

            @can(\App\Support\Permissions::STOCK_VIEW)
                <a href="{{ route('stock.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Unités en stock</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($stats['stockTotal']) }}</p>
                    <p class="mt-1 text-xs font-medium text-emerald-800">Consulter le stock <span aria-hidden="true">→</span></p>
                </a>
            @endcan

            @can(\App\Support\Permissions::STOCK_ALERTS)
                <a href="{{ route('stock.alertes') }}" @class([
                    'rounded-2xl border p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md',
                    'border-amber-200 bg-amber-50/70 hover:border-amber-300' => $stats['stockAlerts'] > 0,
                    'border-slate-200 bg-white hover:border-emerald-200' => $stats['stockAlerts'] === 0,
                ])>
                    <p class="text-sm font-medium text-slate-600">Alertes de stock</p>
                    <p @class([
                        'mt-3 text-3xl font-bold tracking-tight',
                        'text-amber-800' => $stats['stockAlerts'] > 0,
                        'text-slate-950' => $stats['stockAlerts'] === 0,
                    ])>{{ number_format($stats['stockAlerts']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ number_format($stats['expiringSoon']) }} produit(s) à surveiller pour péremption</p>
                </a>
            @endcan

            @can(\App\Support\Permissions::COMMANDES_VIEW)
                <a href="{{ route('commandes.index') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                    <p class="text-sm font-medium text-slate-500">Commandes en attente</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950">{{ number_format($stats['pendingOrders']) }}</p>
                    <p class="mt-1 text-xs font-medium text-emerald-800">Suivre les approvisionnements <span aria-hidden="true">→</span></p>
                </a>
            @endcan

            @if (! auth()->user()->can(\App\Support\Permissions::VENTES_VIEW)
                && ! auth()->user()->can(\App\Support\Permissions::MEDICAMENTS_VIEW)
                && ! auth()->user()->can(\App\Support\Permissions::STOCK_VIEW)
                && ! auth()->user()->can(\App\Support\Permissions::STOCK_ALERTS)
                && ! auth()->user()->can(\App\Support\Permissions::COMMANDES_VIEW))
                <div class="rounded-2xl border border-slate-200 bg-white p-5 text-sm text-slate-600 sm:col-span-2 xl:col-span-4">
                    Votre rôle ne donne pas accès aux indicateurs opérationnels. Contactez l’administrateur de votre entreprise si vous pensez qu’il s’agit d’une erreur.
                </div>
            @else
                <section class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">
                    Votre rôle ne donne pas accès aux indicateurs opérationnels. Contactez l’administrateur de votre entreprise si vous pensez qu’il s’agit d’une erreur.
                </section>
            @endif
        </section>
    @endif

    <div class="mt-7 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-semibold text-slate-900">Activité récente</h2>
                    <p class="mt-1 text-xs text-slate-500">Mouvements, ventes et commandes de votre entreprise</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">7 dernières opérations</span>
            </div>
            <div class="divide-y divide-slate-100 px-5 sm:px-6">
                @forelse ($recentActivity as $activity)
                    <div class="flex flex-wrap items-center justify-between gap-3 py-4">
                        <div class="flex min-w-0 items-start gap-3">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800">{{ $activity['title'] }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-700">{{ $activity['detail'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $activity['date']->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-sm font-medium text-slate-700">Aucune opération récente</p>
                        <p class="mt-1 text-xs text-slate-500">Les opérations apparaîtront ici au fur et à mesure.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Accès rapide</p>
                <h2 class="mt-1 text-lg font-semibold text-slate-900">Les outils de votre équipe</h2>
                <p class="mt-1 text-sm text-slate-500">Accédez directement aux opérations courantes.</p>
            </div>
            <div class="mt-5 grid gap-3">
                @can(\App\Support\Permissions::VENTES_CREATE)
                    <a href="{{ route('ventes.create') }}" class="flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900 transition hover:bg-emerald-100">
                        Enregistrer une vente <span aria-hidden="true">→</span>
                    </a>
                @endcan
                @can(\App\Support\Permissions::MEDICAMENTS_VIEW)
                    <a href="{{ route('medicaments.index') }}" class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Consulter les produits <span aria-hidden="true">→</span>
                    </a>
                @endcan
                @can(\App\Support\Permissions::COMMANDES_VIEW)
                    <a href="{{ route('commandes.index') }}" class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Gérer les commandes <span aria-hidden="true">→</span>
                    </a>
                @endcan
                @can(\App\Support\Permissions::KPIS_VIEW)
                    @if (isset($activeEntreprise) && $activeEntreprise->subscriptionPlan?->kpis_enabled)
                        <a href="{{ route('kpis.index') }}" class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                            Voir les indicateurs KPI <span aria-hidden="true">→</span>
                        </a>
                    @endif
                @endcan
                @role('administrateur')
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Gérer les membres <span aria-hidden="true">→</span>
                    </a>
                @endrole
            </div>
        </section>
    </div>
</x-app-layout>
