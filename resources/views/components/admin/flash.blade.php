@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition.opacity.duration.300ms
        x-init="setTimeout(() => (show = false), 4200)"
        class="pointer-events-none fixed inset-x-0 top-20 z-[100] flex justify-center px-4 sm:top-24"
        role="status"
    >
        <div
            class="pointer-events-auto flex max-w-md items-center gap-3 rounded-2xl border border-emerald-200/80 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-lg shadow-slate-900/10 dark:border-emerald-500/20 dark:bg-slate-900 dark:text-emerald-300"
        >
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </span>
            <span class="flex-1">{{ session('success') }}</span>
            <button type="button" class="rounded-lg p-1 text-emerald-700/70 hover:bg-emerald-50 hover:text-emerald-900 dark:hover:bg-emerald-500/10" @click="show = false">
                <span class="sr-only">Dismiss</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="pointer-events-none fixed inset-x-0 top-20 z-[100] flex justify-center px-4 sm:top-24"
        role="alert"
    >
        <div
            class="pointer-events-auto flex max-w-md items-center gap-3 rounded-2xl border border-red-200/80 bg-white px-4 py-3 text-sm font-medium text-red-800 shadow-lg dark:border-red-500/25 dark:bg-slate-900 dark:text-red-300"
        >
            <span class="flex-1">{{ session('error') }}</span>
            <button type="button" class="rounded-lg p-1 hover:bg-red-50 dark:hover:bg-red-950/40" @click="show = false">
                <span class="sr-only">Dismiss</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif
