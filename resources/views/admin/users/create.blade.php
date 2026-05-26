<x-app-layout>
    <x-slot name="header">Nouvel employé</x-slot>

    <form method="POST" action="{{ route('admin.users.store') }}" class="max-w-4xl">
        @csrf
        @include('admin.users._form', ['user' => null])
    </form>
</x-app-layout>
