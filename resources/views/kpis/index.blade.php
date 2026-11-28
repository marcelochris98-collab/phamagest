<x-app-layout>
    <x-slot name="header">Indicateurs KPI</x-slot>
    <x-slot name="subheader">Une vue détaillée des stocks, des approvisionnements et des ventes.</x-slot>

    <section class="mb-6 flex flex-wrap items-end justify-between gap-4 rounded-2xl bg-emerald-950 px-5 py-5 text-white shadow-sm sm:px-7">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-200">Synthèse commerciale</p>
            <p class="mt-1 text-sm text-emerald-100/80">Chiffre d’affaires réalisé ce mois</p>
            <p class="mt-2 text-3xl font-bold tracking-tight">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} <span class="text-base font-semibold">FCFA</span></p>
        </div>
        <div class="rounded-xl bg-white/10 px-4 py-3 text-sm">
            <p class="text-emerald-200">Ventes validées ce mois</p>
            <p class="mt-1 text-xl font-semibold">{{ number_format($stats['ventes_mois']) }}</p>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Indicateurs détaillés">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Médicaments</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($stats['medicaments']) }}</p>
            <p class="mt-1 text-xs text-slate-500">Références au catalogue</p>
        </article>
        <article class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Unités en stock</p>
            <p class="mt-3 text-3xl font-bold text-emerald-800">{{ number_format($stats['stock_total']) }}</p>
            <p class="mt-1 text-xs text-slate-500">Quantité totale disponible</p>
        </article>
        <article class="rounded-2xl border border-amber-200 bg-amber-50/60 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-800">Alertes stock</p>
            <p class="mt-3 text-3xl font-bold text-amber-900">{{ number_format($stats['alertes_stock']) }}</p>
            <p class="mt-1 text-xs text-amber-900/70">Produits sous le seuil défini</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clients</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($stats['clients']) }}</p>
            <p class="mt-1 text-xs text-slate-500">Clients professionnels</p>
        </article>
        <article class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-sky-800">Commandes en attente</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($stats['commandes_en_attente']) }}</p>
            <p class="mt-1 text-xs text-slate-500">Approvisionnements à suivre</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Péremptions à surveiller</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($stats['peremption_proche']) }}</p>
            <p class="mt-1 text-xs text-slate-500">Dans les trois prochains mois</p>
        </article>
    </section>

    <div class="mt-7 grid gap-6 xl:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h2 class="font-semibold text-slate-900">Derniers mouvements de stock</h2>
                <p class="mt-1 text-xs text-slate-500">Entrées et sorties enregistrées</p>
            </div>
            <ul class="divide-y divide-slate-100 px-5 sm:px-6">
                @forelse ($stats['mouvements_recents'] as $mouvement)
                    <li class="flex items-center justify-between gap-4 py-3.5 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-800">{{ $mouvement->medicament->nom }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $mouvement->type->label() }} · {{ $mouvement->date->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 rounded-lg bg-slate-50 px-3 py-1.5 font-semibold text-slate-700">{{ number_format($mouvement->quantite) }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-slate-500">Aucun mouvement enregistré.</li>
                @endforelse
            </ul>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h2 class="font-semibold text-slate-900">Ventes les plus importantes</h2>
                <p class="mt-1 text-xs text-slate-500">Classement par montant des ventes validées</p>
            </div>
            <ul class="divide-y divide-slate-100 px-5 sm:px-6">
                @forelse ($stats['top_ventes'] as $vente)
                    <li class="flex items-center justify-between gap-4 py-3.5 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-800">{{ $vente->client?->nom ?? 'Client supprimé' }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">Vente #{{ $vente->id }} · {{ $vente->date_vente->format('d/m/Y') }}</p>
                        </div>
                        <span class="shrink-0 font-semibold text-emerald-800">{{ number_format($vente->total, 0, ',', ' ') }} FCFA</span>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-slate-500">Aucune vente validée.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-app-layout>
