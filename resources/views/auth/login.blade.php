<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="mb-6 text-lg font-semibold text-slate-900">Connexion</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" class="block mt-2 w-full rounded-xl" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-2 w-full rounded-xl" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm transition-all duration-300 focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Se souvenir de moi</span>
            </label>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 transition-all duration-300 hover:text-emerald-700" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-300 ease-in-out hover:bg-emerald-700 hover:shadow-md active:scale-[0.98] sm:w-auto"
            >
                Se connecter
            </button>
        </div>
    </form>
</x-guest-layout>
