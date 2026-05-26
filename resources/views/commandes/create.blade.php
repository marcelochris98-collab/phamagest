<x-app-layout>
    <x-slot name="header">Nouvelle commande</x-slot>
    <form method="POST" action="{{ route('commandes.store') }}" class="max-w-4xl">
        @csrf
        <x-pharma.card class="p-6">@include('commandes._form', ['commande' => null])</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Créer la commande</x-pharma.button>
            <x-pharma.button :href="route('commandes.index')" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
</x-app-layout>
