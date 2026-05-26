@props(['color' => 'slate'])

@php
    $classes = match ($color) {
        'emerald' => 'bg-emerald-100 text-emerald-800',
        'amber' => 'bg-amber-100 text-amber-800',
        'red' => 'bg-red-100 text-red-800',
        'blue' => 'bg-blue-100 text-blue-800',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-lg px-2.5 py-1 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
