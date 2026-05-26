<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PharmaGest'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <div class="min-h-screen flex">
        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            @include('layouts.partials.topbar')

            <main class="flex-1 p-6 lg:p-8">
                @include('layouts.partials.flash')

                @isset($header)
                    <div class="mb-8">
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $header }}</h1>
                        @isset($subheader)
                            <p class="mt-1 text-sm text-slate-500">{{ $subheader }}</p>
                        @endisset
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
