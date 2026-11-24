<header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-sm lg:px-8">
    <button
        type="button"
        @click="sidebarOpen = true"
        class="rounded-xl p-2 text-slate-500 transition-all duration-300 hover:bg-slate-100 hover:text-slate-800 lg:hidden"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div class="hidden lg:block">
        <p class="text-sm text-slate-500">Bienvenue,</p>
        <p class="font-medium text-slate-900">{{ auth()->user()->name }}</p>
    </div>

    <div class="ml-auto flex items-center gap-3">
        @if (auth()->user()->isPlatformAdmin())
            <a href="{{ route('platform.companies.index') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                Plateforme
            </a>
        @endif

        @isset($activeEntreprise)
            @if ($availableEntreprises->count() > 1)
                <form method="POST" action="{{ route('entreprise.active') }}" class="flex items-center gap-2">
                    @csrf
                    <label for="active_entreprise_id" class="sr-only">Espace entreprise</label>
                    <select
                        id="active_entreprise_id"
                        name="entreprise_id"
                        class="max-w-48 rounded-xl border-slate-300 py-2 text-sm text-slate-700 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                        @foreach ($availableEntreprises as $entreprise)
                            <option value="{{ $entreprise->id }}" @selected($activeEntreprise->id === $entreprise->id)>
                                {{ $entreprise->nom }}
                            </option>
                        @endforeach
                    </select>
                    <button
                        type="submit"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        Changer
                    </button>
                </form>
            @else
                <span class="hidden text-sm font-medium text-slate-600 sm:inline">{{ $activeEntreprise->nom }}</span>
            @endif
        @endif

        <a
            href="{{ route('profile.edit') }}"
            class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition-all duration-300 ease-in-out hover:bg-slate-100 hover:text-slate-900"
        >
            Profil
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all duration-300 ease-in-out hover:bg-slate-800 hover:shadow-md active:scale-[0.98]"
            >
                Déconnexion
            </button>
        </form>
    </div>
</header>
