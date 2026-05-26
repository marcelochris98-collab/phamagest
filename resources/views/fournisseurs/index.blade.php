<x-app-layout>
    <x-slot name="header">Fournisseurs</x-slot>
    <div class="mb-6 flex justify-end">
        @can(\App\Support\Permissions::FOURNISSEURS_MANAGE)
            <x-pharma.button :href="route('fournisseurs.create')">Nouveau fournisseur</x-pharma.button>
        @endcan
    </div>
    <x-pharma.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Pays</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Contact</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($fournisseurs as $f)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium">{{ $f->nom }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $f->pays }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $f->telephone ?? $f->email ?? '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                @can(\App\Support\Permissions::FOURNISSEURS_MANAGE)
                                    <a href="{{ route('fournisseurs.edit', $f) }}" class="text-sm text-emerald-700 hover:underline">Modifier</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Aucun fournisseur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($fournisseurs->hasPages())<div class="px-6 py-4">{{ $fournisseurs->links() }}</div>@endif
    </x-pharma.card>
</x-app-layout>
