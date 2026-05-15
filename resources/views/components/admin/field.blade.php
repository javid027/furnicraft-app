@props([
    'label',
    'for',
    'hint' => null,
    'required' => false,
])

<div class="space-y-1.5">
    <label for="{{ $for }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    {{ $slot }}
    @if ($hint)
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
</div>
