<x-app-layout>
    <x-slot name="header">Vente #{{ $vente->id }}</x-slot>

    <div class="mb-6 flex flex-wrap gap-3">
        @if ($vente->statut === \App\Enums\VenteStatut::Brouillon)
            @can(\App\Support\Permissions::VENTES_VALIDATE)
                <form method="POST" action="{{ route('ventes.validate', $vente) }}" onsubmit="return confirm('Valider cette vente et déduire le stock ?')">
                    @csrf
                    <x-pharma.button type="submit">Valider la vente</x-pharma.button>
                </form>
            @endcan
            @can(\App\Support\Permissions::VENTES_CREATE)
                <x-pharma.button :href="route('ventes.edit', $vente)" variant="secondary">Modifier</x-pharma.button>
                <form method="POST" action="{{ route('ventes.destroy', $vente) }}" onsubmit="return confirm('Supprimer ce brouillon ?')">
                    @csrf @method('DELETE')
                    <x-pharma.button type="submit" variant="danger">Supprimer</x-pharma.button>
                </form>
            @endcan
        @endif
        @if ($vente->statut === \App\Enums\VenteStatut::Validee)
            @can(\App\Support\Permissions::VENTES_INVOICE)
                <x-pharma.button :href="route('ventes.invoice', $vente)">Télécharger facture PDF</x-pharma.button>
            @endcan
        @endif
        <x-pharma.button :href="route('ventes.index')" variant="ghost">Retour</x-pharma.button>
    </div>

    <x-pharma.card class="p-6 mb-6">
        <dl class="grid gap-4 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">Client</dt><dd class="font-medium">{{ $vente->client->nom }}</dd></div>
            <div><dt class="text-slate-500">Vendeur</dt><dd>{{ $vente->user->name }}</dd></div>
            <div><dt class="text-slate-500">Statut</dt><dd>
                @php $vc = match($vente->statut->value) { 'validee' => 'emerald', 'annulee' => 'red', default => 'slate' }; @endphp
                <x-pharma.badge :color="$vc">{{ $vente->statut->label() }}</x-pharma.badge>
            </dd></div>
            <div><dt class="text-slate-500">Date</dt><dd>{{ $vente->date_vente->format('d/m/Y H:i') }}</dd></div>
        </dl>
    </x-pharma.card>

    <x-pharma.card>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Médicament</th>
                    <th class="px-4 py-3 text-left">Quantité</th>
                    <th class="px-4 py-3 text-left">Prix unit.</th>
                    <th class="px-4 py-3 text-right">Total ligne</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($vente->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->medicament->nom }}</td>
                        <td class="px-4 py-3">{{ $item->quantite }}</td>
                        <td class="px-4 py-3">{{ number_format($item->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($item->quantite * $item->prix_unitaire, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-emerald-50 font-bold text-emerald-900">
                    <td colspan="3" class="px-4 py-3 text-right">Total TTC</td>
                    <td class="px-4 py-3 text-right">{{ number_format($vente->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </x-pharma.card>
</x-app-layout>
