<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Votre espace professionnel</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Connexion</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Heureux de vous retrouver. Connectez-vous pour accéder à votre entreprise.</p>
    </div>

    <nav class="mb-7 grid grid-cols-2 rounded-xl border border-slate-200 bg-slate-100 p-1" aria-label="Accès à PharmaGest">
        <a href="{{ route('login') }}" aria-current="page" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm ring-1 ring-slate-200/70">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 7a4 4 0 1 0-5.7 3.6L4 16v4h4v-3h3v-3h2l1.5-1.5A4 4 0 0 0 15 7Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="11" cy="7" r="1" fill="currentColor"/>
            </svg>
            Se connecter
        </a>
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:text-emerald-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16v14H4zM8 9h8M8 13h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M17 16v4m-2-2h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
            Créer une entreprise
        </a>
    </nav>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" class="text-slate-700" />
            <div class="relative mt-2">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.6"/>
                    <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <x-text-input id="email" class="block w-full rounded-xl border-slate-200 py-3 pl-10 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-600 focus:ring-emerald-600" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nom@entreprise.cm" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ visible: false }">
            <div class="mb-2 flex items-center justify-between">
                <x-input-label for="password" value="Mot de passe" class="text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-emerald-700 transition hover:text-emerald-900" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                @endif
            </div>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="4" y="10" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
                <x-text-input id="password" class="block w-full rounded-xl border-slate-200 py-3 pl-10 pr-12 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-600 focus:ring-emerald-600" x-bind:type="visible ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Votre mot de passe" />
                <button type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-3.5 text-slate-400 transition hover:text-emerald-700" @click="visible = !visible" :aria-label="visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'">
                    <svg x-show="!visible" class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M2.5 12s3.2-6 9.5-6 9.5 6 9.5 6-3.2 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                    <svg x-show="visible" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m3 3 18 18M10.6 6.2A10.7 10.7 0 0 1 12 6c6.3 0 9.5 6 9.5 6a15 15 0 0 1-3.1 3.6M6.2 6.3C3.8 8 2.5 12 2.5 12s3.2 6 9.5 6c1.2 0 2.3-.2 3.2-.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center gap-2.5">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
                <span class="text-sm text-slate-600">Se souvenir de moi</span>
            </label>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 active:scale-[0.99]">
            Se connecter
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Votre entreprise n’a pas encore d’espace ?
        <a class="font-semibold text-emerald-700 underline decoration-emerald-300 underline-offset-4 transition hover:text-emerald-900" href="{{ route('register') }}">Créer un espace entreprise</a>
    </p>
</x-guest-layout>
