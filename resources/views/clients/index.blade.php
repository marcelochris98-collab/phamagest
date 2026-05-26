<x-app-layout>
    <x-slot name="header">Clients grossistes</x-slot>
    <div class="mb-6 flex justify-end">
        @can(\App\Support\Permissions::CLIENTS_MANAGE)
            <x-pharma.button :href="route('clients.create')">Nouveau client</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Téléphone</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($clients as $c)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-medium">{{ $c->nom }}</td>
                        <td class="px-6 py-4"><x-pharma.badge color="blue">{{ $c->type->label() }}</x-pharma.badge></td>
                        <td class="px-6 py-4">{{ $c->telephone ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            @can(\App\Support\Permissions::CLIENTS_MANAGE)
                                <a href="{{ route('clients.edit', $c) }}" class="text-emerald-700 hover:underline">Modifier</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Aucun client.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $clients->links() }}</div>
    </x-pharma.card>
</x-app-layout>
