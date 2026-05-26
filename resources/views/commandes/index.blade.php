<x-app-layout>
    <x-slot name="header">Commandes fournisseurs</x-slot>
    <div class="mb-6 flex justify-end">
        @can(\App\Support\Permissions::COMMANDES_MANAGE)
            <x-pharma.button :href="route('commandes.create')">Nouvelle commande</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fournisseur</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Statut</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($commandes as $c)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3">#{{ $c->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $c->fournisseur->nom }}</td>
                        <td class="px-4 py-3">{{ $c->date_commande->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @php $color = match($c->statut->value) { 'livree' => 'emerald', 'annulee' => 'red', default => 'amber' }; @endphp
                            <x-pharma.badge :color="$color">{{ $c->statut->label() }}</x-pharma.badge>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('commandes.show', $c) }}" class="text-emerald-700 hover:underline">Détail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $commandes->links() }}</div>
    </x-pharma.card>
</x-app-layout>
