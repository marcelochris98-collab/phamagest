<x-app-layout>
    <x-slot name="header">Modifier le fournisseur</x-slot>
    <form method="POST" action="{{ route('fournisseurs.update', $fournisseur) }}" class="max-w-3xl">
        @csrf @method('PUT')
        <x-pharma.card class="p-6">@include('fournisseurs._form')</x-pharma.card>
        <div class="mt-6 flex gap-3">
            <x-pharma.button type="submit">Mettre à jour</x-pharma.button>
            <x-pharma.button :href="route('fournisseurs.index')" variant="ghost">Annuler</x-pharma.button>
        </div>
    </form>
    @can(\App\Support\Permissions::FOURNISSEURS_MANAGE)
        <form method="POST" action="{{ route('fournisseurs.destroy', $fournisseur) }}" class="mt-4" onsubmit="return confirm('Supprimer ce fournisseur ?')">
            @csrf @method('DELETE')
            <x-pharma.button type="submit" variant="danger">Supprimer</x-pharma.button>
        </form>
    @endcan
</x-app-layout>
