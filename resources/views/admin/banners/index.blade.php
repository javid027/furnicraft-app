@extends('layouts.app')

@section('title', 'Banners')

@section('content')
    <x-admin.page-header
        title="Banners"
        description="Hero creatives, promos, and seasonal drops for the storefront."
        :breadcrumbs="[['label' => 'Marketing', 'href' => route('banners.index')], ['label' => 'Banners']]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('banners.create') }}" variant="primary" size="md">Add banner</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card :padding="false">
        <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-full text-left text-sm">
                <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Preview</th>
                        <th class="px-5 py-3">Title</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($banners as $banner)
                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                @if ($banner->image)
                                    <img src="{{ $banner->image_url }}" alt="" class="h-14 w-28 rounded-xl border border-slate-200 object-cover dark:border-slate-700" loading="lazy" />
                                @else
                                    <span class="text-xs text-slate-400">No image</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">{{ $banner->title ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <x-admin.badge :variant="$banner->is_active ? 'success' : 'neutral'">{{ $banner->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <x-admin.button href="{{ route('banners.edit', $banner) }}" variant="ghost" size="sm">Edit</x-admin.button>
                                    <form action="{{ route('banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Delete this banner?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" variant="danger" size="sm">Delete</x-admin.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center text-sm text-slate-500">No banners found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
            {{ $banners->links() }}
        </div>
    </x-admin.card>
@endsection
