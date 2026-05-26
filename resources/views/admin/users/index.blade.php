<x-app-layout>
    <x-slot name="header">Gestion des employés</x-slot>

    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between mb-6">
        <p class="text-sm text-slate-500 max-w-xl">
            Réservé à l'administrateur : créez les comptes, attribuez un rôle (Admin, Magasinier, Vendeur) et configurez les permissions granulaires.
        </p>
        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-in-out hover:bg-emerald-700 hover:shadow-md active:scale-[0.98]"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel employé
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Employé</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Rôle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Permissions</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="transition-colors duration-200 hover:bg-slate-50/80">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium capitalize text-slate-700">
                                    {{ $user->getRoleNames()->first() ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">
                                    @if ($user->hasRole('administrateur'))
                                        Accès total
                                    @else
                                        {{ $user->permissions->count() }} permission(s)
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="rounded-lg px-3 py-1.5 text-sm font-medium text-emerald-700 transition-all duration-300 hover:bg-emerald-50"
                                    >
                                        Modifier
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Supprimer cet employé ?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 transition-all duration-300 hover:bg-red-50"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                Aucun employé enregistré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
