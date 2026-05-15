@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <x-admin.page-header
        title="Categories"
        description="Structure navigation and merchandising stories."
        :breadcrumbs="[['label' => 'Catalog', 'href' => route('categories.index')], ['label' => 'Categories']]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('categories.create') }}" variant="primary" size="md">Add category</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card :padding="false">
        <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-full text-left text-sm">
                <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Image</th>
                        <th class="px-5 py-3">Featured</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($categories as $category)
                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">{{ $category->name }}</td>
                            <td class="px-5 py-3.5">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="" class="h-14 w-14 rounded-xl border border-slate-200 object-cover dark:border-slate-700" loading="lazy" />
                                @else
                                    <span class="text-xs text-slate-400">No image</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($category->is_feature)
                                    <x-admin.badge variant="info">Featured</x-admin.badge>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <x-admin.badge :variant="$category->status === 'active' ? 'success' : 'neutral'">{{ ucfirst($category->status) }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <x-admin.button href="{{ route('categories.edit', $category) }}" variant="ghost" size="sm">Edit</x-admin.button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" variant="danger" size="sm">Delete</x-admin.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-sm text-slate-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
            {{ $categories->links() }}
        </div>
    </x-admin.card>
@endsection
