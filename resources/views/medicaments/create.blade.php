<x-app-layout>
    <x-slot name="header">Nouveau médicament</x-slot>
    <form method="POST" action="{{ route('medicaments.store') }}" class="max-w-3xl">
        @csrf
        <x-pharma.card class="p-6">@include('medicaments._form')</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Enregistrer</x-pharma.button>
            <x-pharma.button :href="route('medicaments.index')" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
</x-app-layout>
