<x-app-layout>
    <x-slot name="header">Nouvelle vente</x-slot>
    <form method="POST" action="{{ route('ventes.store') }}" class="max-w-4xl">
        @csrf
        <x-pharma.card class="p-6">@include('ventes._form', ['vente' => null])</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Enregistrer en brouillon</x-pharma.button>
            <x-pharma.button :href="route('ventes.index')" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
</x-app-layout>
