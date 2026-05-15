@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div
    {{ $attributes->class([
        'rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none',
        'p-5 sm:p-6' => $padding,
    ]) }}
>
    @if ($title || $subtitle || isset($header))
        <div class="mb-5 flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                @if ($title)
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($header)
                <div class="flex shrink-0 items-center gap-2">{{ $header }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</div>
