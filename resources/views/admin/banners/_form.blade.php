@php
    $editing = isset($banner);
@endphp

<form action="{{ $editing ? route('banners.update', $banner) : route('banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <x-admin.field label="Title" for="title" :required="true">
        <input type="text" name="title" id="title" value="{{ old('title', $banner->title ?? '') }}" required class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
    </x-admin.field>

    <x-admin.field label="Description" for="description" :required="true">
        <textarea name="description" id="description" rows="4" required class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('description', $banner->description ?? '') }}</textarea>
    </x-admin.field>

    <x-admin.field label="{{ $editing ? 'Image (optional)' : 'Image' }}" for="imageInput" hint="{{ $editing ? 'Leave blank to keep current artwork.' : 'Landscape, under ~2MB.' }}">
        @if (!$editing)
            <input type="file" name="image" id="imageInput" accept="image/*" required class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-semibold file:text-violet-700 dark:file:bg-violet-500/10 dark:file:text-violet-300" />
        @else
            <input type="file" name="image" id="imageInput" accept="image/*" class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-semibold file:text-violet-700 dark:file:bg-violet-500/10 dark:file:text-violet-300" />
        @endif
        @if ($editing && $banner->image)
            <img src="{{ asset('storage/' . $banner->image) }}" id="imagePreview" class="mt-3 max-h-48 rounded-2xl border border-slate-200 object-cover dark:border-slate-700" alt="Banner preview" />
        @else
            <img id="imagePreview" src="#" alt="Preview" class="mt-3 hidden max-h-48 rounded-2xl border border-slate-200 object-cover dark:border-slate-700" />
        @endif
    </x-admin.field>

    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/50">
        <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30" {{ old('is_active', ($banner->is_active ?? true)) ? 'checked' : '' }} />
        <span class="text-sm font-medium text-slate-800 dark:text-slate-200">Visible on storefront</span>
    </label>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-slate-800 sm:flex-row sm:justify-between">
        <x-admin.button href="{{ route('banners.index') }}" variant="secondary" type="button">Cancel</x-admin.button>
        <x-admin.button type="submit" variant="primary">{{ $editing ? 'Update banner' : 'Create banner' }}</x-admin.button>
    </div>
</form>

<script>
    document.getElementById('imageInput')?.addEventListener('change', function (event) {
        const [file] = event.target.files;
        const preview = document.getElementById('imagePreview');
        if (!preview) {
            return;
        }
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
</script>
