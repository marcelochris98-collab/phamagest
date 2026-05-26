@php
    $selectedRole = old('role', $user?->getRoleNames()->first() ?? 'magasinier');
    $selectedPermissions = old('permissions', $user ? $user->permissions->pluck('name')->toArray() : []);
    $isAdminRole = $selectedRole === 'administrateur';
@endphp

<div
    x-data="{
        role: @js($selectedRole),
        get isAdmin() { return this.role === 'administrateur'; }
    }"
    class="space-y-8"
>
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-6">
        <h2 class="text-lg font-semibold text-slate-900">Informations du compte</h2>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nom complet</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user?->name) }}"
                    required
                    class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm transition-all duration-300 focus:border-emerald-500 focus:ring-emerald-500"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user?->email) }}"
                    required
                    class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm transition-all duration-300 focus:border-emerald-500 focus:ring-emerald-500"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Mot de passe {{ $user ? '(laisser vide pour conserver)' : '' }}
                </label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    {{ $user ? '' : 'required' }}
                    class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm transition-all duration-300 focus:border-emerald-500 focus:ring-emerald-500"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm transition-all duration-300 focus:border-emerald-500 focus:ring-emerald-500"
                />
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-6">
        <h2 class="text-lg font-semibold text-slate-900">Rôle principal</h2>
        <p class="text-sm text-slate-500">Un seul rôle par employé. L'administrateur dispose de tous les droits.</p>

        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ($roles as $role)
                <label
                    class="relative flex cursor-pointer flex-col rounded-xl border-2 p-4 transition-all duration-300 ease-in-out"
                    :class="role === @js($role->name)
                        ? 'border-emerald-500 bg-emerald-50/50 shadow-sm'
                        : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                >
                    <input
                        type="radio"
                        name="role"
                        value="{{ $role->name }}"
                        x-model="role"
                        class="sr-only"
                        {{ $selectedRole === $role->name ? 'checked' : '' }}
                    />
                    <span class="font-semibold capitalize text-slate-900">{{ $role->name }}</span>
                    <span class="mt-1 text-xs text-slate-500">
                        @if ($role->name === 'administrateur')
                            Maître du système
                        @elseif ($role->name === 'magasinier')
                            Stock & approvisionnement
                        @else
                            Ventes & clients
                        @endif
                    </span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div
        class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-6 transition-opacity duration-300"
        :class="isAdmin ? 'opacity-60 pointer-events-none' : ''"
    >
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Permissions spécifiques</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Cochez les actions autorisées pour cet employé (hors administrateur).
                </p>
            </div>
            <span
                x-show="isAdmin"
                x-cloak
                class="rounded-lg bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800"
            >
                Toutes les permissions
            </span>
        </div>

        @foreach ($permissionGroups as $groupLabel => $permissionKeys)
            <div class="border-t border-slate-100 pt-6 first:border-0 first:pt-0">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">{{ $groupLabel }}</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach ($permissionKeys as $permissionKey)
                        @if ($permissionKey === \App\Support\Permissions::USERS_MANAGE)
                            @continue
                        @endif
                        <label class="group flex items-start gap-3 rounded-xl border border-slate-100 p-3 transition-all duration-300 hover:border-emerald-200 hover:bg-emerald-50/30 cursor-pointer">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permissionKey }}"
                                @checked(in_array($permissionKey, $selectedPermissions, true))
                                :disabled="isAdmin"
                                class="mt-0.5 rounded border-slate-300 text-emerald-600 shadow-sm transition-all duration-300 focus:ring-emerald-500"
                            />
                            <span class="text-sm text-slate-700 group-hover:text-slate-900">
                                {{ $permissionLabels[$permissionKey] ?? $permissionKey }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
        <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
    </div>

    <div class="flex flex-wrap items-center gap-4">
        <button
            type="submit"
            class="inline-flex items-center rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-in-out hover:bg-emerald-700 hover:shadow-md active:scale-[0.98]"
        >
            {{ $user ? 'Enregistrer les modifications' : 'Créer l\'employé' }}
        </button>
        <a
            href="{{ route('admin.users.index') }}"
            class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition-all duration-300 hover:bg-slate-100"
        >
            Annuler
        </a>
    </div>
</div>
