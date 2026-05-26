<x-app-layout>
    <x-slot name="header">Médicaments</x-slot>
    <div class="mb-6 flex justify-end">
        @can(\App\Support\Permissions::MEDICAMENTS_MANAGE)
            <x-pharma.button :href="route('medicaments.create')">Nouveau médicament</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nom / DCI</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Seuil</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Péremption</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($medicaments as $m)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $m->nom }}</p>
                                <p class="text-xs text-slate-500">{{ $m->dci }} · {{ $m->forme }} {{ $m->dosage }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if ($m->stock_critique)
                                    <x-pharma.badge color="amber" class="animate-pulse-soft">{{ $m->quantite_stock }}</x-pharma.badge>
                                @else
                                    {{ $m->quantite_stock }}
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $m->seuil_alerte }}</td>
                            <td class="px-4 py-3">{{ $m->date_peremption?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                @can(\App\Support\Permissions::MEDICAMENTS_MANAGE)
                                    <a href="{{ route('medicaments.edit', $m) }}" class="text-emerald-700 hover:underline">Modifier</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $medicaments->links() }}</div>
    </x-pharma.card>
</x-app-layout>
