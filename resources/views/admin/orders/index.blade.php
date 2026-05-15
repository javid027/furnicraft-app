@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    @php
        $statusVariant = fn (string $s) => match ($s) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'neutral',
        };
    @endphp

    <x-admin.page-header
        title="Orders"
        description="Search the queue, print invoices, and keep fulfillment on track."
        :breadcrumbs="[['label' => 'Sales', 'href' => route('admin.orders.index')], ['label' => 'Orders']]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('admin.orders.index') }}" variant="secondary" size="md">Reset filters</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total orders</p>
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $totalOrders }}</p>
            <x-admin.badge variant="primary" class="mt-3">All time</x-admin.badge>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Revenue</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($totalRevenue, 2) }}</p>
            <x-admin.badge variant="success" class="mt-3">Gross</x-admin.badge>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Avg. order value</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($averageOrderValue, 2) }}</p>
            <x-admin.badge variant="info" class="mt-3">Blended</x-admin.badge>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Delivered</p>
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $statusCounts['delivered'] ?? 0 }}</p>
            <x-admin.badge variant="success" class="mt-3">Completed</x-admin.badge>
        </div>
    </div>

    <x-admin.card title="Filters" subtitle="Slice by customer, status, or fulfillment window." class="mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid gap-4 md:grid-cols-12 md:items-end">
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
                            placeholder="Order ID or customer"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                        />
                    </div>
                </x-admin.field>
            </div>
            <div class="md:col-span-3">
                <x-admin.field label="Status" for="status">
                    <select
                        name="status"
                        id="status"
                        class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    >
                        <option value="">All statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </x-admin.field>
            </div>
            <div class="md:col-span-2">
                <x-admin.field label="From" for="from_date">
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                </x-admin.field>
            </div>
            <div class="md:col-span-2">
                <x-admin.field label="To" for="to_date">
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                </x-admin.field>
            </div>
            <div class="md:col-span-1">
                <x-admin.button type="submit" variant="primary" class="w-full justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v5.45a8.11 8.11 0 01-.314 2.19l-1.333 4.012a1.082 1.082 0 01-1.025.708H5.66a1.082 1.082 0 01-1.025-.708l-1.333-4.012A8.11 8.11 0 013 11.224V5.932c0-.54.384-1.006.917-1.096C6.545 3.232 9.245 3 12 3z" /></svg>
                </x-admin.button>
            </div>
        </form>
        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">{{ $orders->total() }} result{{ $orders->total() === 1 ? '' : 's' }} match your criteria.</p>
    </x-admin.card>

    <x-admin.card :padding="false">
        @if ($orders->count())
            <div class="overflow-x-auto rounded-2xl">
                <table class="min-w-full text-left text-sm">
                    <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3">Placed</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($orders as $order)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">#{{ $order->id }}</td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $order->customer->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $order->customer->email ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-admin.badge :variant="$statusVariant($order->status)">{{ ucfirst($order->status) }}</x-admin.badge>
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <x-admin.button href="{{ route('admin.orders.show', $order) }}" variant="primary" size="sm">View</x-admin.button>
                                        <x-admin.button href="{{ route('admin.orders.invoice', $order) }}" variant="secondary" size="sm">Invoice</x-admin.button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-20 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5" /></svg>
                </div>
                <p class="text-base font-semibold text-slate-900 dark:text-white">No orders found</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Loosen filters or pick another date range.</p>
            </div>
        @endif

        @if ($orders->count())
            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>
                    Showing
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $orders->firstItem() }}</span>
                    –
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $orders->lastItem() }}</span>
                    of
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $orders->total() }}</span>
                </p>
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection
