<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">Rejoindre PharmaGest</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Créer votre espace entreprise</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Renseignez les informations de votre entreprise et de son administrateur.</p>
    </div>

    <nav class="mb-6 grid grid-cols-2 rounded-xl border border-slate-200 bg-slate-100 p-1" aria-label="Accès à PharmaGest">
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:text-emerald-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 7a4 4 0 1 0-5.7 3.6L4 16v4h4v-3h3v-3h2l1.5-1.5A4 4 0 0 0 15 7Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="11" cy="7" r="1" fill="currentColor"/>
            </svg>
            Se connecter
        </a>
        <a href="{{ route('register') }}" aria-current="page" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-3 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm ring-1 ring-slate-200/70">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 5h16v14H4zM8 9h8M8 13h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M17 16v4m-2-2h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
            Créer une entreprise
        </a>
    </nav>

    <div class="mb-6 flex gap-3 rounded-xl border border-emerald-100 bg-emerald-50/70 p-3.5 text-sm leading-5 text-emerald-900">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3 19 6v5c0 4.7-3 8-7 10-4-2-7-5.3-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p>Votre dossier sera vérifié avant l’activation de l’espace. Préparez les justificatifs d’enregistrement et d’autorisation.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <section aria-labelledby="company-information-heading">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-800">01</span>
                <div>
                    <h2 id="company-information-heading" class="text-sm font-semibold text-slate-900">Informations de l’entreprise</h2>
                    <p class="text-xs text-slate-500">Coordonnées et références légales</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input-label for="entreprise" value="Nom de l’entreprise" class="text-slate-700" />
                    <x-text-input id="entreprise" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="text" name="entreprise" :value="old('entreprise')" required autofocus autocomplete="organization" placeholder="Ex. Pharmacie du Centre" />
                    <x-input-error :messages="$errors->get('entreprise')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="entreprise_email" value="E-mail de l’entreprise (facultatif)" class="text-slate-700" />
                    <x-text-input id="entreprise_email" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="email" name="entreprise_email" :value="old('entreprise_email')" autocomplete="organization" placeholder="contact@entreprise.cm" />
                    <x-input-error :messages="$errors->get('entreprise_email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="entreprise_telephone" value="Téléphone (facultatif)" class="text-slate-700" />
                    <x-text-input id="entreprise_telephone" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="tel" name="entreprise_telephone" :value="old('entreprise_telephone')" autocomplete="tel" placeholder="+237 6XX XXX XXX" />
                    <x-input-error :messages="$errors->get('entreprise_telephone')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="entreprise_adresse" value="Adresse de l’entreprise (facultatif)" class="text-slate-700" />
                    <textarea id="entreprise_adresse" name="entreprise_adresse" rows="2" autocomplete="street-address" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600">{{ old('entreprise_adresse') }}</textarea>
                    <x-input-error :messages="$errors->get('entreprise_adresse')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="numero_enregistrement" value="Numéro d’enregistrement" class="text-slate-700" />
                    <x-text-input id="numero_enregistrement" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="text" name="numero_enregistrement" :value="old('numero_enregistrement')" required />
                    <x-input-error :messages="$errors->get('numero_enregistrement')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="numero_autorisation" value="Numéro d’autorisation pharmaceutique" class="text-slate-700" />
                    <x-text-input id="numero_autorisation" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="text" name="numero_autorisation" :value="old('numero_autorisation')" required />
                    <x-input-error :messages="$errors->get('numero_autorisation')" class="mt-2" />
                </div>
            </div>
        </section>

        <section aria-labelledby="company-documents-heading" class="border-t border-slate-100 pt-6">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-800">02</span>
                <div>
                    <h2 id="company-documents-heading" class="text-sm font-semibold text-slate-900">Justificatifs de l’entreprise</h2>
                    <p class="text-xs text-slate-500">PDF, JPG ou PNG · 10 Mo maximum par fichier</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="document_enregistrement" value="Justificatif d’enregistrement" class="text-slate-700" />
                    <input id="document_enregistrement" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white p-2.5 text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-emerald-800" type="file" name="document_enregistrement" accept=".pdf,.jpg,.jpeg,.png" required />
                    <x-input-error :messages="$errors->get('document_enregistrement')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="document_autorisation" value="Justificatif d’autorisation" class="text-slate-700" />
                    <input id="document_autorisation" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white p-2.5 text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-emerald-800" type="file" name="document_autorisation" accept=".pdf,.jpg,.jpeg,.png" required />
                    <x-input-error :messages="$errors->get('document_autorisation')" class="mt-2" />
                </div>
            </div>
        </section>

        <section aria-labelledby="administrator-heading" class="border-t border-slate-100 pt-6">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-800">03</span>
                <div>
                    <h2 id="administrator-heading" class="text-sm font-semibold text-slate-900">Compte administrateur</h2>
                    <p class="text-xs text-slate-500">Ce compte gérera l’espace de l’entreprise</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Votre nom" class="text-slate-700" />
                    <x-text-input id="name" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Nom complet" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Adresse e-mail" class="text-slate-700" />
                    <x-text-input id="email" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="vous@entreprise.cm" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Mot de passe" class="text-slate-700" />
                    <x-text-input id="password" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le mot de passe" class="text-slate-700" />
                    <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-600 focus:ring-emerald-600" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </section>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 active:scale-[0.99]">
            Envoyer le dossier de l’entreprise
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Vous avez déjà un compte ?
        <a class="font-semibold text-emerald-700 underline decoration-emerald-300 underline-offset-4 transition hover:text-emerald-900" href="{{ route('login') }}">Se connecter</a>
    </p>
</x-guest-layout>
