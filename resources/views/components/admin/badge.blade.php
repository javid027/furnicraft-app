@props([
    'variant' => 'neutral',
])

@php
    $styles = [
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/15 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/25',
        'warning' => 'bg-amber-50 text-amber-800 ring-amber-600/15 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/25',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/15 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/25',
        'info' => 'bg-sky-50 text-sky-700 ring-sky-600/15 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-500/25',
        'primary' => 'bg-violet-50 text-violet-700 ring-violet-600/15 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-500/25',
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-600/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-500/20',
    ];
    $class = $styles[$variant] ?? $styles['neutral'];
@endphp

<span
    {{ $attributes->class([
        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset',
        $class,
    ]) }}
>
    {{ $slot }}
</span>
