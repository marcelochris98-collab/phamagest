<x-app-layout>
    <x-slot name="header">Modifier la commande #{{ $commande->id }}</x-slot>
    <form method="POST" action="{{ route('commandes.update', $commande) }}" class="max-w-4xl">
        @csrf @method('PUT')
        <x-pharma.card class="p-6">@include('commandes._form')</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Enregistrer</x-pharma.button>
            <x-pharma.button :href="route('commandes.show', $commande)" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
</x-app-layout>
