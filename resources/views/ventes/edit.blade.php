<x-app-layout>
    <x-slot name="header">Modifier la vente #{{ $vente->id }}</x-slot>
    <form method="POST" action="{{ route('ventes.update', $vente) }}" class="max-w-4xl">
        @csrf @method('PUT')
        <x-pharma.card class="p-6">@include('ventes._form')</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Mettre à jour</x-pharma.button>
            <x-pharma.button :href="route('ventes.show', $vente)" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
</x-app-layout>
