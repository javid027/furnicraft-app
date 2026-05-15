@props([
    'title',
    'description' => null,
    'breadcrumbs' => [],
])

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0 space-y-2">
        @if (count($breadcrumbs))
            <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400 dark:hover:text-violet-300">
                    Home
                </a>
                @foreach ($breadcrumbs as $crumb)
                    <span class="text-slate-300 dark:text-slate-600" aria-hidden="true">/</span>
                    @if (!empty($crumb['href']) && !$loop->last)
                        <a href="{{ $crumb['href'] }}" class="font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400">
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                {{ $title }}
            </h1>
            @if ($description)
                <p class="mt-1 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                    {{ $description }}
                </p>
            @endif
        </div>
    </div>

    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
