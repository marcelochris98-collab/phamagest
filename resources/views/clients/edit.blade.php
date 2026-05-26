<x-app-layout>
    <x-slot name="header">Modifier le client</x-slot>
    <form method="POST" action="{{ route('clients.update', $client) }}" class="max-w-3xl">
        @csrf @method('PUT')
        <x-pharma.card class="p-6">@include('clients._form')</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Mettre à jour</x-pharma.button>
            <x-pharma.button :href="route('clients.index')" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
    <form method="POST" action="{{ route('clients.destroy', $client) }}" class="mt-4" onsubmit="return confirm('Supprimer ?')">
        @csrf @method('DELETE')
        <x-pharma.button type="submit" variant="danger">Supprimer</x-pharma.button>
    </form>
</x-app-layout>
