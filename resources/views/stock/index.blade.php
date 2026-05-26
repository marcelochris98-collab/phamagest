<x-app-layout>
    <x-slot name="header">État du stock</x-slot>
    <div class="mb-4 flex gap-3">
        @can(\App\Support\Permissions::STOCK_ALERTS)
            <x-pharma.button :href="route('stock.alertes')" variant="secondary">Alertes</x-pharma.button>
        @endcan
        @can(\App\Support\Permissions::STOCK_MOVEMENTS)
            <x-pharma.button :href="route('stock.mouvements')" variant="ghost">Mouvements</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Médicament</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fournisseur</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Seuil</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($medicaments as $m)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-medium">{{ $m->nom }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $m->fournisseur?->nom ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($m->stock_critique)
                                <x-pharma.badge color="amber">{{ $m->quantite_stock }}</x-pharma.badge>
                            @else
                                {{ $m->quantite_stock }}
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $m->seuil_alerte }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $medicaments->links() }}</div>
    </x-pharma.card>
</x-app-layout>
