<x-app-layout>
    <x-slot name="header">Ventes</x-slot>
    <div class="mb-6 flex justify-end">
        @can(\App\Support\Permissions::VENTES_CREATE)
            <x-pharma.button :href="route('ventes.create')">Nouvelle vente</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Client</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Vendeur</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Statut</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($ventes as $v)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3">#{{ $v->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $v->client->nom }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $v->user->name }}</td>
                        <td class="px-4 py-3">{{ number_format($v->total, 0, ',', ' ') }} F</td>
                        <td class="px-4 py-3">
                            @php $vc = match($v->statut->value) { 'validee' => 'emerald', 'annulee' => 'red', default => 'slate' }; @endphp
                            <x-pharma.badge :color="$vc">{{ $v->statut->label() }}</x-pharma.badge>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('ventes.show', $v) }}" class="text-emerald-700 hover:underline">Détail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $ventes->links() }}</div>
    </x-pharma.card>
</x-app-layout>
