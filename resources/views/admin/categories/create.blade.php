@extends('layouts.app')

@section('title', 'New category')

@section('content')
    <x-admin.page-header
        title="Create category"
        description="Define taxonomy that powers navigation and filters."
        :breadcrumbs="[
            ['label' => 'Categories', 'href' => route('categories.index')],
            ['label' => 'Create'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('categories.index') }}" variant="secondary" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card>
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <x-admin.field label="Name" for="name" :required="true">
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
            </x-admin.field>

            <x-admin.field label="Image" for="image" :required="true" hint="Square asset under ~1MB.">
                <input type="file" name="image" id="image" accept="image/*" required onchange="previewImage(event)" class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:font-semibold file:text-violet-700 dark:file:bg-violet-500/10 dark:file:text-violet-300" />
                <img id="image-preview" class="mt-3 hidden h-28 w-28 rounded-2xl border border-slate-200 object-cover dark:border-slate-700" alt="" />
            </x-admin.field>

            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/50">
                <input type="checkbox" name="is_feature" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30" {{ old('is_feature') ? 'checked' : '' }} />
                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">Featured collection</span>
            </label>

            <x-admin.field label="Status" for="status">
                <select name="status" id="status" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </x-admin.field>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-slate-800 sm:flex-row sm:justify-between">
                <x-admin.button href="{{ route('categories.index') }}" variant="secondary" type="button">Cancel</x-admin.button>
                <x-admin.button type="submit" variant="primary">Save category</x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <script>
        function previewImage(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('image-preview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        }
    </script>
@endsection
