@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])

@php
    $classes = match ($variant) {
        'primary' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm',
        'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'ghost' => 'text-slate-600 hover:bg-slate-100',
        default => 'bg-emerald-600 text-white hover:bg-emerald-700',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-300 ease-in-out hover:shadow-md active:scale-[0.98] {$classes}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-300 ease-in-out hover:shadow-md active:scale-[0.98] {$classes}"]) }}>
        {{ $slot }}
    </button>
@endif
