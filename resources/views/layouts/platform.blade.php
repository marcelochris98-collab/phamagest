<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration plateforme') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-slate-950 text-white shadow-xl transition-transform duration-300 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        aria-label="Navigation administration plateforme"
    >
        <a href="{{ route('platform.dashboard') }}" class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-950/30">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </span>
            <span>
                <span class="block text-base font-bold tracking-tight">PharmaGest</span>
                <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-300">Administration</span>
            </span>
        </a>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Pilotage</p>
            <a href="{{ route('platform.dashboard') }}"
               @class([
                   'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition',
                   'bg-emerald-500/15 text-emerald-200 ring-1 ring-inset ring-emerald-400/20' => request()->routeIs('platform.dashboard'),
                   'text-slate-300 hover:bg-white/[0.06] hover:text-white' => ! request()->routeIs('platform.dashboard'),
               ])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 13h8V3H3v10Zm0 8h8v-4H3v4Zm12 0h6V11h-6v10Zm0-18v4h6V3h-6Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                </svg>
                <span class="flex-1">Vue d’ensemble</span>
                @if (request()->routeIs('platform.dashboard'))
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                @endif
            </a>

            <p class="mb-3 mt-8 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Gestion SaaS</p>
            <a href="{{ route('platform.companies.index') }}"
               @class([
                   'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition',
                   'bg-emerald-500/15 text-emerald-200 ring-1 ring-inset ring-emerald-400/20' => request()->routeIs('platform.companies.*'),
                   'text-slate-300 hover:bg-white/[0.06] hover:text-white' => ! request()->routeIs('platform.companies.*'),
               ])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M3 21h18M5 21V6l7-3 7 3v15M9 9h.01M15 9h.01M9 13h.01M15 13h.01M10 21v-4h4v4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="flex-1">Entreprises</span>
            </a>
            <a href="{{ route('platform.plans.index') }}"
               @class([
                   'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition',
                   'bg-emerald-500/15 text-emerald-200 ring-1 ring-inset ring-emerald-400/20' => request()->routeIs('platform.plans.*'),
                   'text-slate-300 hover:bg-white/[0.06] hover:text-white' => ! request()->routeIs('platform.plans.*'),
               ])>
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 5h16v14H4zM8 9h8M8 13h5m5 3 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="flex-1">Forfaits</span>
            </a>
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-2xl bg-white/[0.06] p-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-sm font-bold text-emerald-200">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden" style="display: none;"></div>

    <div class="min-h-screen min-w-0 lg:pl-72">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="rounded-xl p-2 text-slate-600 transition hover:bg-slate-100 lg:hidden" aria-label="Ouvrir la navigation">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>
                <div>
                    <p class="hidden text-xs font-medium text-slate-500 sm:block">PharmaGest <span class="mx-1 text-slate-300">/</span> Administration plateforme</p>
                    <h1 class="text-base font-semibold text-slate-900 sm:mt-0.5">@yield('page_title', 'Administration')</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 sm:inline-flex">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Super-administrateur
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
