<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PharmaGest') }} — Espace sécurisé</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased text-slate-900">
    <div class="min-h-screen lg:grid lg:grid-cols-2">
        <aside class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-950 px-10 py-8 text-white lg:flex lg:flex-col xl:px-16">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-32 -top-36 h-96 w-96 rounded-full border border-emerald-300/10"></div>
                <div class="absolute -left-20 -top-24 h-72 w-72 rounded-full border border-emerald-300/10"></div>
                <div class="absolute -bottom-40 -right-36 h-[34rem] w-[34rem] rounded-full border-[3rem] border-white/[0.025]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_20%,rgba(16,185,129,0.14),transparent_48%)]"></div>
            </div>

            <a href="{{ route('login') }}" class="relative z-10 inline-flex w-fit items-center gap-3" aria-label="PharmaGest, accueil de connexion">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-emerald-300 shadow-lg shadow-black/10">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <span>
                    <span class="block text-sm font-bold tracking-wide">PharmaGest</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-200/70">Gestion pharmaceutique</span>
                </span>
            </a>

            <div class="relative z-10 my-auto max-w-xl py-16">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200/15 bg-white/[0.06] px-3.5 py-1.5 text-xs font-medium text-emerald-100/90">
                    <svg class="h-3.5 w-3.5 text-emerald-300" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3 19 6v5c0 4.7-3 8-7 10-4-2-7-5.3-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Votre activité, dans un espace sécurisé
                </span>

                <h1 class="mt-7 max-w-lg text-4xl font-semibold leading-tight tracking-tight text-white xl:text-5xl">
                    La gestion de votre pharmacie,
                    <span class="text-emerald-300">en toute confiance.</span>
                </h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-slate-300">
                    Approvisionnements, stock et ventes réunis dans un seul espace pour votre entreprise.
                </p>

                <ul class="mt-10 space-y-5">
                    <li class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] text-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                <path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v8.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-white">Stocks sous contrôle</span>
                            <span class="mt-1 block text-sm text-slate-400">Suivez les niveaux et les dates de péremption.</span>
                        </span>
                    </li>
                    <li class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] text-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M3 7h12v11H3zM15 11h3l3 3v4h-6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                <circle cx="7.5" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/>
                                <circle cx="18" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/>
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-white">Approvisionnements maîtrisés</span>
                            <span class="mt-1 block text-sm text-slate-400">Gérez commandes, fournisseurs et réceptions.</span>
                        </span>
                    </li>
                    <li class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.06] text-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                <path d="M14 3v5h5M8 13h8M8 17h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-white">Ventes et facturation</span>
                            <span class="mt-1 block text-sm text-slate-400">Retrouvez vos ventes et factures au même endroit.</span>
                        </span>
                    </li>
                </ul>
            </div>

            <footer class="relative z-10 flex items-center justify-between gap-4 text-xs text-slate-500">
                <span>© {{ now()->year }} PharmaGest. Tous droits réservés.</span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Accès réservé aux entreprises
                </span>
            </footer>
        </aside>

        <main class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8 lg:overflow-y-auto lg:px-12">
            <div class="w-full max-w-xl">
                <div class="mb-8 flex flex-col items-center lg:mb-9">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-700 text-white shadow-lg shadow-emerald-900/15">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </span>
                    <span class="mt-2 text-xs font-semibold tracking-wide text-slate-500 lg:sr-only">PharmaGest</span>
                </div>

                {{ $slot }}

                <p class="mt-8 flex items-center justify-center gap-2 text-center text-xs text-slate-400">
                    <svg class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.7"/>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                    Connexion sécurisée à votre espace PharmaGest
                </p>
            </div>
        </main>
    </div>
</body>
</html>
