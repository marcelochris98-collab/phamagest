<x-app-layout>
    <x-slot name="header">Alertes stock & péremption</x-slot>

    <div class="grid gap-8 lg:grid-cols-2">
        <x-pharma.card class="p-6">
            <h3 class="flex items-center gap-2 font-semibold text-amber-800">
                Stock critique
                <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span><span class="relative h-2 w-2 rounded-full bg-amber-500"></span></span>
            </h3>
            <ul class="mt-4 space-y-3">
                @forelse ($stockCritique as $m)
                    <li class="flex justify-between rounded-xl bg-amber-50/80 px-4 py-3 text-sm">
                        <span>{{ $m->nom }}</span>
                        <span class="font-bold text-amber-800">{{ $m->quantite_stock }} / {{ $m->seuil_alerte }}</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucune alerte de rupture.</li>
                @endforelse
            </ul>
        </x-pharma.card>

        <x-pharma.card class="p-6">
            <h3 class="font-semibold text-red-800">Péremption sous 3 mois</h3>
            <ul class="mt-4 space-y-3">
                @forelse ($peremptionProche as $m)
                    <li class="flex justify-between rounded-xl bg-red-50/50 px-4 py-3 text-sm">
                        <span>{{ $m->nom }}</span>
                        <span class="font-medium text-red-700">{{ $m->date_peremption->format('d/m/Y') }}</span>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucune péremption proche.</li>
                @endforelse
            </ul>
        </x-pharma.card>
    </div>
</x-app-layout>
