<x-app-layout>
    <x-slot name="header">Historique des mouvements</x-slot>
    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Médicament</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Qté</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Motif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($mouvements as $mv)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3">{{ $mv->date->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $mv->medicament->nom }}</td>
                        <td class="px-4 py-3">
                            <x-pharma.badge :color="$mv->type->value === 'entree' ? 'emerald' : 'red'">{{ $mv->type->label() }}</x-pharma.badge>
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $mv->quantite }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $mv->motif }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $mouvements->links() }}</div>
    </x-pharma.card>
</x-app-layout>
