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

    <div class="flex items-center gap-3 ml-auto">
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
