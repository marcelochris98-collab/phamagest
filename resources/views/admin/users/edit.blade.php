<x-app-layout>
    <x-slot name="header">Modifier l'employé</x-slot>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-4xl">
        @csrf
        @method('PUT')
        @include('admin.users._form', ['user' => $user])
    </form>
</x-app-layout>
