<aside
    class="fixed inset-y-0 left-0 z-40 w-72 bg-white border-r border-slate-200/80 shadow-sm transform transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-full flex-col">
        <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm transition-transform duration-300 hover:scale-105">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">PharmaGest</p>
                <p class="text-xs text-slate-500">Douala · B2B</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
            @foreach ($navItems as $item)
                @php
                    $active = request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route']));
                @endphp
                <a
                    href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-300 ease-in-out
                        {{ $active
                            ? 'bg-emerald-50 text-emerald-800 shadow-sm'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition-all duration-300 ease-in-out
                        {{ $active ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-700' }}">
                        @include('layouts.partials.icons.' . $item['icon'])
                    </span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if (!empty($item['pulse']))
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-100 p-4">
            <div class="rounded-2xl bg-slate-50 p-4 transition-all duration-300 hover:bg-slate-100">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Connecté</p>
                <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs capitalize text-slate-500">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
        </div>
    </div>
</aside>

<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"
    style="display: none;"
></div>
