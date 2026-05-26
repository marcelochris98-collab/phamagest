<x-app-layout>
    <x-slot name="header">Tableau de bord</x-slot>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @can(\App\Support\Permissions::STOCK_ALERTS)
            <a href="{{ route('stock.alertes') }}" class="rounded-2xl border border-amber-200/80 bg-amber-50/50 p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                <div class="flex items-center gap-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Alertes stock</p>
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                    </span>
                </div>
                <p class="mt-2 text-3xl font-bold text-amber-800">{{ $alertesStock }}</p>
                <p class="mt-1 text-sm text-amber-700/80">Sous seuil d'alerte</p>
            </a>
        @endcan

        @can(\App\Support\Permissions::STOCK_ALERTS)
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Péremption (&lt; 3 mois)</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $peremptionProche }}</p>
            </div>
        @endcan

        @can(\App\Support\Permissions::COMMANDES_VIEW)
            <a href="{{ route('commandes.index') }}" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Commandes</p>
                <p class="mt-2 text-lg font-semibold text-emerald-700">Voir les approvisionnements →</p>
            </a>
        @endcan

        @can(\App\Support\Permissions::VENTES_VIEW)
            <a href="{{ route('ventes.index') }}" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ventes</p>
                <p class="mt-2 text-lg font-semibold text-emerald-700">Gérer les ventes →</p>
            </a>
        @endcan
    </div>

    <x-pharma.card class="mt-8 p-6">
        <h2 class="text-lg font-semibold text-slate-900">Bienvenue sur PharmaGest</h2>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Importation et revente de médicaments en gros — Douala, Cameroun.
        </p>
        @role('administrateur')
            <div class="mt-4 flex flex-wrap gap-3">
                <x-pharma.button :href="route('admin.users.index')" variant="ghost">Employés</x-pharma.button>
                @can(\App\Support\Permissions::KPIS_VIEW)
                    <x-pharma.button :href="route('kpis.index')" variant="ghost">KPIs</x-pharma.button>
                @endcan
            </div>
        @endrole
    </x-pharma.card>
</x-app-layout>
