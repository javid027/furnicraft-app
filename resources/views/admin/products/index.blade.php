@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <x-admin.page-header
        title="Products"
        description="Search, filter, and curate your catalog without leaving this view."
        :breadcrumbs="[
            ['label' => 'Catalog', 'href' => route('products.index')],
            ['label' => 'Products'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('products.create') }}" variant="primary" size="md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add product
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card title="Filters" subtitle="Refine by name, category, or inventory posture." class="mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="grid gap-4 md:grid-cols-12 md:items-end">
            <div class="md:col-span-4">
                <x-admin.field label="Search" for="search">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Name or keyword"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                        />
                    </div>
                </x-admin.field>
            </div>
            <div class="md:col-span-3">
                <x-admin.field label="Category" for="category_id">
                    <select
                        name="category_id"
                        id="category_id"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </x-admin.field>
            </div>
            <div class="md:col-span-3">
                <x-admin.field label="Stock" for="stock_filter">
                    <select
                        name="stock_filter"
                        id="stock_filter"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="">All</option>
                        <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Low (&lt; 10)</option>
                        <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>Healthy (≥ 10)</option>
                    </select>
                </x-admin.field>
            </div>
            <div class="flex gap-2 md:col-span-2">
                <x-admin.button type="submit" variant="primary" class="flex-1">Apply</x-admin.button>
                <x-admin.button href="{{ route('products.index') }}" variant="secondary" class="flex-1">Reset</x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card :padding="false">
        <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-full text-left text-sm">
                <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3 text-right">Price</th>
                        <th class="px-5 py-3">Color</th>
                        <th class="px-5 py-3 text-right">Qty</th>
                        <th class="px-5 py-3">Featured</th>
                        <th class="px-5 py-3">Image</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($products as $product)
                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                @if ($product->stock < 10)
                                    <x-admin.badge variant="danger">Low · {{ $product->stock }}</x-admin.badge>
                                @else
                                    <x-admin.badge variant="success">{{ $product->stock }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($product->price, 2) }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $product->color_name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right tabular-nums text-slate-600 dark:text-slate-300">{{ $product->quantity ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                @if ($product->is_featured)
                                    <x-admin.badge variant="primary">Featured</x-admin.badge>
                                @else
                                    <x-admin.badge variant="neutral">Standard</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($product->image)
                                    <a href="{{ asset('storage/' . $product->image) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" class="h-12 w-12 rounded-xl border border-slate-200 object-cover dark:border-slate-700" loading="lazy" />
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">No image</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <x-admin.button href="{{ route('products.edit', $product) }}" variant="ghost" size="sm">Edit</x-admin.button>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" variant="danger" size="sm">Delete</x-admin.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-300">No products match these filters.</p>
                                <p class="mt-1 text-xs text-slate-500">Try clearing search or add a new SKU.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>
                    Showing <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->firstItem() }}</span>
                    –
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->lastItem() }}</span>
                    of
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $products->total() }}</span>
                </p>
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection
