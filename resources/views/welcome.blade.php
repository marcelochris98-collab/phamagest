<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PharmaGest rassemble la gestion des produits, des stocks, des approvisionnements et des ventes dans un espace dédié à votre entreprise.">
    <meta name="theme-color" content="#f6faf8">
    <title>PharmaGest — La gestion pharmaceutique, plus claire</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased text-slate-900">
    <header class="relative z-10 border-b border-slate-200/70 bg-white/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-5 py-4 sm:px-8 lg:px-10" aria-label="Navigation principale">
            <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center gap-3" aria-label="PharmaGest, accueil">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-700 text-white shadow-lg shadow-emerald-900/15">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <span>
                    <span class="block text-base font-bold tracking-tight text-slate-950">PharmaGest</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">Gestion pharmaceutique</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-medium text-slate-600 lg:flex">
                <a href="#fonctionnalites" class="transition hover:text-emerald-800">Fonctionnalités</a>
                <a href="#organisation" class="transition hover:text-emerald-800">Pour votre équipe</a>
                <a href="#questions" class="transition hover:text-emerald-800">Questions fréquentes</a>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <a href="{{ route('login') }}" class="hidden rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 sm:inline-flex">Se connecter</a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                    Créer mon espace
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="relative isolate overflow-hidden bg-[#f5f9f7]">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -right-40 -top-56 h-[38rem] w-[38rem] rounded-full border border-emerald-900/[0.06]"></div>
                <div class="absolute -right-20 -top-36 h-[30rem] w-[30rem] rounded-full border border-emerald-900/[0.06]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_76%_18%,rgba(16,185,129,0.12),transparent_38%)]"></div>
            </div>

            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 py-16 sm:px-8 sm:py-20 lg:grid-cols-[0.94fr_1.06fr] lg:gap-10 lg:px-10 lg:py-24">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white/80 px-3.5 py-2 text-xs font-semibold tracking-wide text-emerald-900 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        CONÇU POUR LES ÉQUIPES PHARMACEUTIQUES
                    </span>

                    <h1 class="mt-7 text-4xl font-semibold leading-[1.08] tracking-[-0.04em] text-slate-950 sm:text-5xl lg:text-[3.6rem]">
                        Votre activité pharmaceutique,
                        <span class="text-emerald-700">plus claire à chaque étape.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                        Produits, approvisionnements, stocks, clients et ventes : PharmaGest rassemble les opérations essentielles de votre entreprise dans un espace simple à suivre et à partager avec votre équipe.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/15 transition hover:-translate-y-0.5 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                            Démarrer mon essai d’un mois
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="#fonctionnalites" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white/70 px-5 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:bg-white hover:text-emerald-900">
                            Découvrir les fonctionnalités
                        </a>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-medium text-slate-500">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Accès immédiat à l’inscription
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Aucun justificatif demandé pour démarrer
                        </span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-2xl lg:ml-auto">
                    <div aria-hidden="true" class="absolute -inset-5 rounded-[2rem] bg-emerald-200/40 blur-2xl"></div>
                    <div class="relative overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white p-3 shadow-[0_28px_90px_-32px_rgba(15,55,42,0.32)] sm:p-4">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-2 pb-4 sm:px-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-700 text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Vue d’ensemble</p>
                                    <p class="text-xs text-slate-500">Les repères utiles de votre activité</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Aperçu illustratif</span>
                        </div>

                        <div class="grid gap-3 py-4 sm:grid-cols-3">
                            <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-800">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v8.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                                    </span>
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                </div>
                                <p class="mt-3 text-xs font-medium text-slate-500">Catalogue & stock</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">Niveaux et alertes</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-800">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h12v11H3zM15 11h3l3 3v4h-6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7.5" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/></svg>
                                    </span>
                                    <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                                </div>
                                <p class="mt-3 text-xs font-medium text-slate-500">Approvisionnements</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">Commandes suivies</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-800">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3v5h5M8 13h8M8 17h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <span class="h-1.5 w-1.5 rounded-full bg-violet-500"></span>
                                </div>
                                <p class="mt-3 text-xs font-medium text-slate-500">Ventes & factures</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">Opérations retrouvées</p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-[1.15fr_0.85fr]">
                            <div class="rounded-xl border border-slate-100 p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Suivi des opérations</p>
                                        <p class="mt-1 text-xs text-slate-500">Entrées et sorties de stock</p>
                                    </div>
                                    <span class="rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-800">Historique</span>
                                </div>
                                <div class="mt-5 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 19V5m-6 6 6-6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2 w-28 rounded-full bg-slate-200"></div>
                                            <div class="mt-1.5 h-1.5 w-20 rounded-full bg-slate-100"></div>
                                        </div>
                                        <span class="h-2 w-12 rounded-full bg-emerald-100"></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14m6-6-6 6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2 w-24 rounded-full bg-slate-200"></div>
                                            <div class="mt-1.5 h-1.5 w-16 rounded-full bg-slate-100"></div>
                                        </div>
                                        <span class="h-2 w-12 rounded-full bg-amber-100"></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="h-2 w-32 rounded-full bg-slate-200"></div>
                                            <div class="mt-1.5 h-1.5 w-20 rounded-full bg-slate-100"></div>
                                        </div>
                                        <span class="h-2 w-12 rounded-full bg-sky-100"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                                <div class="flex items-start gap-2.5">
                                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 9v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold text-amber-950">Alertes utiles</p>
                                        <p class="mt-1 text-[11px] leading-5 text-amber-900/75">Repérez les stocks bas et les dates de péremption proches.</p>
                                    </div>
                                </div>
                                <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-amber-100">
                                    <div class="h-full w-2/3 rounded-full bg-amber-400"></div>
                                </div>
                                <p class="mt-2 text-[10px] font-medium text-amber-900/70">Aperçu de l’espace de travail</p>
                            </div>
                        </div>
                    </div>
                    <p class="relative mt-3 text-center text-[11px] text-slate-500">Illustration de l’interface — les informations affichées ici sont indicatives.</p>
                </div>
            </div>
        </section>

        <section class="border-y border-slate-100 bg-white" aria-label="Les essentiels de PharmaGest">
            <div class="mx-auto grid max-w-7xl gap-5 px-5 py-7 sm:grid-cols-3 sm:px-8 lg:px-10">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v8.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    </span>
                    <div><p class="text-sm font-semibold text-slate-900">Le stock, mieux suivi</p><p class="mt-0.5 text-xs text-slate-500">Niveaux, alertes et mouvements</p></div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-800">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h12v11H3zM15 11h3l3 3v4h-6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7.5" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/></svg>
                    </span>
                    <div><p class="text-sm font-semibold text-slate-900">Les flux, reliés entre eux</p><p class="mt-0.5 text-xs text-slate-500">Réception de commande et ventes validées</p></div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-800">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m18 0v-2a4 4 0 0 0-3-3.9M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-7.9a4 4 0 0 1 0 7.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <div><p class="text-sm font-semibold text-slate-900">Une équipe bien organisée</p><p class="mt-0.5 text-xs text-slate-500">Des accès selon les rôles</p></div>
                </div>
            </div>
        </section>

        <section id="fonctionnalites" class="scroll-mt-20 px-5 py-20 sm:px-8 sm:py-24 lg:px-10">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Une gestion qui suit le terrain</span>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Tout le quotidien de votre activité, au même endroit.</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">Chaque fonctionnalité répond à une étape concrète : savoir ce que vous avez, ce qu’il faut commander, et ce qui a été vendu.</p>
                </div>

                <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-800 transition group-hover:bg-emerald-700 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m4.5 7.8 7.5 4.3 7.5-4.3M12 12v8.5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Catalogue & produits</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Retrouvez les produits avec leurs informations utiles : DCI, forme, dosage, quantité disponible, seuil d’alerte, date de péremption et fournisseur associé.</p>
                        <p class="mt-4 text-xs font-semibold text-emerald-800">Un catalogue plus facile à consulter</p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-amber-200 hover:shadow-xl hover:shadow-amber-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-800 transition group-hover:bg-amber-600 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 9v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Stocks & péremptions</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Suivez les quantités et recevez des repères lorsque le stock atteint le seuil défini ou lorsqu’une date de péremption approche.</p>
                        <p class="mt-4 text-xs font-semibold text-amber-800">Moins de surprises dans les rayons</p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-800 transition group-hover:bg-sky-700 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h12v11H3zM15 11h3l3 3v4h-6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7.5" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Fournisseurs & commandes</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Préparez vos commandes fournisseur, suivez leur statut et enregistrez leur réception. Les quantités reçues alimentent alors le stock et son historique.</p>
                        <p class="mt-4 text-xs font-semibold text-sky-800">De la commande à la réception</p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-violet-200 hover:shadow-xl hover:shadow-violet-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-800 transition group-hover:bg-violet-700 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3v5h5M8 13h8M8 17h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Ventes & factures</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Enregistrez une vente pour une pharmacie ou une clinique. À sa validation, PharmaGest contrôle le stock, suit la sortie et permet de récupérer une facture PDF.</p>
                        <p class="mt-4 text-xs font-semibold text-violet-800">Une vente validée, une trace claire</p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-800 transition group-hover:bg-indigo-700 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3v18h18M7 14l4-4 4 3 6-7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Indicateurs de gestion</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Consultez les produits, unités en stock, alertes, commandes en attente, ventes du mois et chiffre d’affaires, ainsi que les mouvements récents.</p>
                        <p class="mt-4 text-xs font-semibold text-indigo-800">Des repères pour décider</p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-rose-200 hover:shadow-xl hover:shadow-rose-950/[0.05]">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-800 transition group-hover:bg-rose-700 group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m18 0v-2a4 4 0 0 0-3-3.9M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-7.9a4 4 0 0 1 0 7.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">Clients & équipe</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Centralisez vos clients professionnels et donnez à chaque collaborateur un compte adapté à son rôle : administration, magasin ou vente.</p>
                        <p class="mt-4 text-xs font-semibold text-rose-800">Des responsabilités mieux réparties</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="organisation" class="scroll-mt-20 overflow-hidden bg-slate-950 px-5 py-20 text-white sm:px-8 sm:py-24 lg:px-10">
            <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                <div class="max-w-xl">
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">Une équipe, un espace organisé</span>
                    <h2 class="mt-4 text-3xl font-semibold leading-tight tracking-tight sm:text-4xl">Chacun sait quoi faire. Les informations restent au même endroit.</h2>
                    <p class="mt-5 text-base leading-7 text-slate-300">L’administrateur de l’entreprise crée les accès des collaborateurs et leur attribue un rôle. Les données métier de chaque entreprise restent séparées de celles des autres espaces.</p>
                    <a href="{{ route('register') }}" class="mt-7 inline-flex items-center gap-2 text-sm font-semibold text-emerald-300 transition hover:text-white">
                        Créer un espace entreprise
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <article class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 19 6v5c0 4.7-3 8-7 10-4-2-7-5.3-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-base font-semibold">Administrateur</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">Organise l’espace, gère les utilisateurs et consulte les indicateurs autorisés.</p>
                    </article>
                    <article class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-400/10 text-sky-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h12v11H3zM15 11h3l3 3v4h-6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7.5" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.8" stroke="currentColor" stroke-width="1.6"/></svg>
                        </span>
                        <h3 class="mt-5 text-base font-semibold">Magasinier</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">Suit les produits, prépare les commandes et enregistre les réceptions.</p>
                    </article>
                    <article class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-400/10 text-violet-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 3v5h5M8 13h8M8 17h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <h3 class="mt-5 text-base font-semibold">Vendeur</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-400">Enregistre les ventes, contrôle les quantités et retrouve les factures.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="px-5 py-20 sm:px-8 sm:py-24 lg:px-10">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Un parcours simple</span>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">De l’inscription au suivi quotidien.</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">Commencez par créer votre espace, puis organisez progressivement vos opérations.</p>
                </div>

                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-6 sm:p-7">
                        <span class="text-sm font-bold tracking-widest text-emerald-700">ÉTAPE 01</span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-950">Créez votre espace</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Renseignez le nom de votre entreprise et créez le compte de son administrateur. Votre essai d’un mois démarre à l’inscription.</p>
                    </article>
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-6 sm:p-7">
                        <span class="text-sm font-bold tracking-widest text-emerald-700">ÉTAPE 02</span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-950">Mettez en place vos opérations</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Ajoutez vos produits, fournisseurs et clients, puis invitez les personnes qui participeront à la gestion.</p>
                    </article>
                    <article class="relative rounded-2xl border border-slate-200 bg-white p-6 sm:p-7">
                        <span class="text-sm font-bold tracking-widest text-emerald-700">ÉTAPE 03</span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-950">Suivez les mouvements</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Enregistrez les commandes et les ventes. Les réceptions et ventes validées alimentent l’historique de stock.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="px-5 pb-20 sm:px-8 sm:pb-24 lg:px-10">
            <div class="mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-emerald-700 px-6 py-10 text-white shadow-2xl shadow-emerald-900/15 sm:px-10 sm:py-12 lg:px-14">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div class="max-w-2xl">
                        <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">UN MOIS POUR PRENDRE VOS REPÈRES</span>
                        <h2 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl">Prêt à mieux voir l’ensemble de votre activité ?</h2>
                        <p class="mt-4 text-sm leading-6 text-emerald-50/90 sm:text-base">Créez l’espace de votre entreprise et découvrez les outils PharmaGest à votre rythme.</p>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3.5 text-sm font-semibold text-emerald-800 shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-700">
                        Créer mon espace
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <section id="questions" class="scroll-mt-20 border-t border-slate-100 bg-slate-50/70 px-5 py-20 sm:px-8 sm:py-24 lg:px-10">
            <div class="mx-auto grid max-w-7xl gap-12 lg:grid-cols-[0.72fr_1.28fr]">
                <div>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Questions fréquentes</span>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">Avant de commencer.</h2>
                    <p class="mt-4 text-sm leading-6 text-slate-600">Quelques réponses pour savoir précisément ce que PharmaGest vous permet de faire aujourd’hui.</p>
                </div>

                <div class="space-y-3">
                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-emerald-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-slate-900">
                            À qui s’adresse PharmaGest ?
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition group-open:rotate-45 group-open:bg-emerald-100 group-open:text-emerald-800" aria-hidden="true">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </span>
                        </summary>
                        <p class="mt-3 pr-8 text-sm leading-6 text-slate-600">PharmaGest est conçu pour les entreprises qui gèrent des produits pharmaceutiques, leurs approvisionnements et leurs ventes auprès de clients professionnels comme les pharmacies et les cliniques.</p>
                    </details>

                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-emerald-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-slate-900">
                            Comment le stock est-il mis à jour ?
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition group-open:rotate-45 group-open:bg-emerald-100 group-open:text-emerald-800" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                        </summary>
                        <p class="mt-3 pr-8 text-sm leading-6 text-slate-600">La réception d’une commande ajoute les quantités reçues. La validation d’une vente vérifie la disponibilité puis enregistre la sortie correspondante. Chaque mouvement est consultable dans l’historique.</p>
                    </details>

                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-emerald-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-slate-900">
                            Puis-je donner un accès à mes employés ?
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition group-open:rotate-45 group-open:bg-emerald-100 group-open:text-emerald-800" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                        </summary>
                        <p class="mt-3 pr-8 text-sm leading-6 text-slate-600">Oui. L’administrateur peut créer des comptes et leur attribuer les rôles disponibles. Chaque compte travaille dans l’espace de son entreprise.</p>
                    </details>

                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-emerald-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-slate-900">
                            Que se passe-t-il après l’essai d’un mois ?
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition group-open:rotate-45 group-open:bg-emerald-100 group-open:text-emerald-800" aria-hidden="true"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                        </summary>
                        <p class="mt-3 pr-8 text-sm leading-6 text-slate-600">L’accès après l’essai dépend du statut d’abonnement géré par l’administration de la plateforme. Le choix autonome de forfaits et le paiement en ligne ne sont pas encore disponibles.</p>
                    </details>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-5 py-7 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5" aria-label="PharmaGest, accueil">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-700 text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/></svg>
                </span>
                <span class="text-sm font-bold text-slate-900">PharmaGest</span>
            </a>
            <p class="text-xs text-slate-500">© {{ now()->year }} PharmaGest. La gestion pharmaceutique, plus claire.</p>
            <div class="flex items-center gap-5 text-sm font-medium text-slate-600">
                <a href="{{ route('login') }}" class="transition hover:text-emerald-800">Connexion</a>
                <a href="{{ route('register') }}" class="transition hover:text-emerald-800">Créer un espace</a>
            </div>
        </div>
    </footer>
</body>
</html>
