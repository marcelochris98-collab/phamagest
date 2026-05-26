<x-app-layout>
    <x-slot name="header">Indicateurs KPI</x-slot>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Médicaments</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['medicaments'] }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Unités en stock</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['stock_total']) }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-amber-600">Alertes stock</p>
            <p class="mt-2 text-3xl font-bold text-amber-800">{{ $stats['alertes_stock'] }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Clients</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['clients'] }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Commandes en attente</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['commandes_en_attente'] }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <p class="text-xs font-semibold uppercase text-slate-400">Ventes ce mois</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['ventes_mois'] }}</p>
        </x-pharma.card>
        <x-pharma.card class="p-6 sm:col-span-2">
            <p class="text-xs font-semibold uppercase text-emerald-600">CA du mois (FCFA)</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</p>
        </x-pharma.card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-pharma.card class="p-6">
            <h3 class="font-semibold text-slate-900">Derniers mouvements de stock</h3>
            <ul class="mt-4 space-y-3">
                @forelse ($stats['mouvements_recents'] as $m)
                    <li class="flex justify-between text-sm border-b border-slate-50 pb-2">
                        <span>{{ $m->medicament->nom }} — <span class="capitalize">{{ $m->type->label() }}</span></span>
                        <span class="font-medium">{{ $m->quantite }}</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucun mouvement.</li>
                @endforelse
            </ul>
        </x-pharma.card>
        <x-pharma.card class="p-6">
            <h3 class="font-semibold text-slate-900">Top ventes (montant)</h3>
            <ul class="mt-4 space-y-3">
                @forelse ($stats['top_ventes'] as $v)
                    <li class="flex justify-between text-sm border-b border-slate-50 pb-2">
                        <span>#{{ $v->id }} — {{ $v->client->nom }}</span>
                        <span class="font-medium">{{ number_format($v->total, 0, ',', ' ') }} F</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucune vente validée.</li>
                @endforelse
            </ul>
        </x-pharma.card>
    </div>
</x-app-layout>
