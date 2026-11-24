<x-guest-layout>
    <h2 class="mb-2 text-lg font-semibold text-slate-900">Créer votre espace entreprise</h2>
    <p class="mb-6 text-sm text-slate-500">Inscrivez votre entreprise et créez le compte de son administrateur.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="entreprise" value="Nom de l’entreprise" />
            <x-text-input id="entreprise" class="mt-2 block w-full rounded-xl" type="text" name="entreprise" :value="old('entreprise')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('entreprise')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="entreprise_email" value="E-mail de l’entreprise (facultatif)" />
            <x-text-input id="entreprise_email" class="mt-2 block w-full rounded-xl" type="email" name="entreprise_email" :value="old('entreprise_email')" autocomplete="organization" />
            <x-input-error :messages="$errors->get('entreprise_email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="entreprise_telephone" value="Téléphone de l’entreprise (facultatif)" />
            <x-text-input id="entreprise_telephone" class="mt-2 block w-full rounded-xl" type="tel" name="entreprise_telephone" :value="old('entreprise_telephone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('entreprise_telephone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="entreprise_adresse" value="Adresse de l’entreprise (facultatif)" />
            <textarea id="entreprise_adresse" name="entreprise_adresse" rows="2" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('entreprise_adresse') }}</textarea>
            <x-input-error :messages="$errors->get('entreprise_adresse')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="name" value="Votre nom" />
            <x-text-input id="name" class="mt-2 block w-full rounded-xl" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-xl" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
        >
            Créer mon espace
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Vous avez déjà un compte ?
        <a class="font-medium text-emerald-700 hover:text-emerald-800" href="{{ route('login') }}">Se connecter</a>
    </p>
</x-guest-layout>
