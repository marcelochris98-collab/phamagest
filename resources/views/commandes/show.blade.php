<x-app-layout>
    <x-slot name="header">Commande #{{ $commande->id }}</x-slot>

    <div class="mb-6 flex flex-wrap gap-3">
        @if ($commande->statut === \App\Enums\CommandeStatut::EnAttente)
            @can(\App\Support\Permissions::COMMANDES_RECEIVE)
                <form method="POST" action="{{ route('commandes.receive', $commande) }}" onsubmit="return confirm('Réceptionner cette commande et mettre à jour le stock ?')">
                    @csrf
                    <x-pharma.button type="submit">Réceptionner (entrée stock)</x-pharma.button>
                </form>
            @endcan
            @can(\App\Support\Permissions::COMMANDES_MANAGE)
                <x-pharma.button :href="route('commandes.edit', $commande)" variant="secondary">Modifier</x-pharma.button>
                <form method="POST" action="{{ route('commandes.cancel', $commande) }}" onsubmit="return confirm('Annuler cette commande ?')">
                    @csrf
                    <x-pharma.button type="submit" variant="danger">Annuler</x-pharma.button>
                </form>
            @endcan
        @endif
        <x-pharma.button :href="route('commandes.index')" variant="ghost">Retour</x-pharma.button>
    </div>

    <x-pharma.card class="p-6 mb-6">
        <dl class="grid gap-4 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">Fournisseur</dt><dd class="font-medium">{{ $commande->fournisseur->nom }}</dd></div>
            <div><dt class="text-slate-500">Statut</dt><dd>
                @php $sc = match($commande->statut->value) { 'livree' => 'emerald', 'annulee' => 'red', default => 'amber' }; @endphp
                <x-pharma.badge :color="$sc">{{ $commande->statut->label() }}</x-pharma.badge>
            </dd></div>
            <div><dt class="text-slate-500">Date commande</dt><dd>{{ $commande->date_commande->format('d/m/Y') }}</dd></div>
            <div><dt class="text-slate-500">Livraison prévue</dt><dd>{{ $commande->date_livraison_prevue?->format('d/m/Y') ?? '—' }}</dd></div>
        </dl>
    </x-pharma.card>

    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Médicament</th>
                    <th class="px-4 py-3 text-left">Quantité</th>
                    <th class="px-4 py-3 text-left">Prix unit.</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php $total = 0; @endphp
                @foreach ($commande->items as $item)
                    @php $line = $item->quantite * $item->prix_unitaire; $total += $line; @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $item->medicament->nom }}</td>
                        <td class="px-4 py-3">{{ $item->quantite }}</td>
                        <td class="px-4 py-3">{{ number_format($item->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($line, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 font-semibold">
                    <td colspan="3" class="px-4 py-3 text-right">Total</td>
                    <td class="px-4 py-3 text-right">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </x-pharma.card>
</x-app-layout>
