@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $fmtTrend = function (float $v): string {
        $sign = $v > 0 ? '+' : '';
        return $sign . number_format($v, 1) . '%';
    };

    $sparkPoints = function ($values, int $w = 120, int $h = 36): string {
        $arr = $values instanceof \Illuminate\Support\Collection ? $values->values()->all() : array_values((array) $values);
        $max = max($arr ?: [1]);
        $min = min($arr ?: [0]);
        $range = max(1e-6, $max - $min);
        $n = count($arr);
        if ($n < 2) {
            return '';
        }
        $pts = [];
        foreach ($arr as $i => $v) {
            $x = ($i / ($n - 1)) * $w;
            $y = $h - (($v - $min) / $range) * $h;
            $pts[] = round($x, 2) . ',' . round($y, 2);
        }
        return implode(' ', $pts);
    };
@endphp

@section('content')
    <x-admin.page-header
        title="Dashboard"
        description="Monitor store health, revenue, and fulfillment at a glance."
        :breadcrumbs="[['label' => 'Overview']]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('products.create') }}" variant="secondary" size="md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New product
            </x-admin.button>
            <x-admin.button href="{{ route('admin.orders.index') }}" variant="primary" size="md">
                View orders
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $statCards = [
                [
                    'label' => 'Total revenue',
                    'value' => '₹' . number_format($totalRevenue, 2),
                    'trend' => $trends['revenue'],
                    'icon' => 'currency',
                    'accent' => 'from-violet-500 to-indigo-600',
                ],
                [
                    'label' => 'Orders',
                    'value' => number_format($totalOrders),
                    'trend' => $trends['orders'],
                    'icon' => 'cart',
                    'accent' => 'from-indigo-500 to-violet-600',
                ],
                [
                    'label' => 'Customers',
                    'value' => number_format($totalCustomers),
                    'trend' => $trends['customers'],
                    'icon' => 'users',
                    'accent' => 'from-sky-500 to-indigo-600',
                ],
                [
                    'label' => 'Products live',
                    'value' => number_format($totalProducts),
                    'trend' => $trends['products'],
                    'icon' => 'box',
                    'accent' => 'from-emerald-500 to-teal-600',
                ],
            ];
        @endphp

        @foreach ($statCards as $card)
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-900/5 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900/80">
                <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-gradient-to-br opacity-[0.12] blur-2xl transition group-hover:opacity-20 {{ $card['accent'] }}"></div>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $card['value'] }}</p>
                        <p class="mt-2 flex items-center gap-1.5 text-xs font-semibold {{ $card['trend'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            @if ($card['trend'] >= 0)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            @endif
                            {{ $fmtTrend($card['trend']) }} <span class="font-normal text-slate-400">vs last month</span>
                        </p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $card['accent'] }} shadow-violet-500/20">
                        @if ($card['icon'] === 'currency')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-9h6a3 3 0 010 6H9m6 0a3 3 0 010 6H6" /></svg>
                        @elseif ($card['icon'] === 'cart')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3H15a3 3 0 00-3-3m-6-6V9a6 6 0 0112 0v1.5" /></svg>
                        @elseif ($card['icon'] === 'users')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" /></svg>
                        @else
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        @endif
                    </div>
                </div>
                @php
                    $sparkData = match ($loop->iteration) {
                        1 => $revenueData,
                        2 => $orderData,
                        3 => $customerData,
                        4 => $productData,
                        default => collect(),
                    };
                    $pts = $sparkPoints($sparkData);
                    $sparkClass = match ($loop->iteration) {
                        1 => 'text-violet-500/80 dark:text-violet-400/80',
                        2 => 'text-indigo-500/80 dark:text-indigo-400/80',
                        3 => 'text-sky-500/80 dark:text-sky-400/80',
                        4 => 'text-emerald-500/80 dark:text-emerald-400/80',
                        default => 'text-slate-400',
                    };
                @endphp
                @if ($pts)
                    <svg class="mt-4 h-9 w-full {{ $sparkClass }}" viewBox="0 0 120 36" preserveAspectRatio="none">
                        <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="{{ $pts }}" />
                    </svg>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <x-admin.card title="Orders" subtitle="Monthly volume" class="lg:col-span-1">
            <div class="h-52">
                <canvas id="ordersChart"></canvas>
            </div>
        </x-admin.card>
        <x-admin.card title="Customers" subtitle="New sign-ups" class="lg:col-span-1">
            <div class="h-52">
                <canvas id="customersChart"></canvas>
            </div>
        </x-admin.card>
        <x-admin.card title="Revenue" subtitle="Recognized sales" class="lg:col-span-1">
            <div class="h-52">
                <canvas id="revenueChart"></canvas>
            </div>
        </x-admin.card>
    </div>

    <div class="mt-6 grid gap-4 xl:grid-cols-3">
        <x-admin.card title="Recent orders" subtitle="Latest five checkouts" class="xl:col-span-2" :padding="false">
            <div class="overflow-x-auto rounded-2xl">
                <table class="min-w-full text-left text-sm">
                    <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Total</th>
                            <th class="px-5 py-3 text-right">Date</th>
                            <th class="px-5 py-3 text-right"> </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($latestOrders as $order)
                            <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                                <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">#{{ $order->id }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-900 dark:text-white">{{ $order->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $os = $order->status;
                                        $ov = match ($os) {
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                            'pending' => 'warning',
                                            'processing', 'shipped' => 'info',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <x-admin.badge :variant="$ov">{{ ucfirst($os) }}</x-admin.badge>
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-5 py-3.5 text-right text-slate-500 dark:text-slate-400">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <x-admin.button href="{{ route('admin.orders.show', $order) }}" variant="ghost" size="sm">View</x-admin.button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300">No orders yet</p>
                                    <p class="mt-1 text-xs text-slate-500">When sales come in, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <div class="space-y-4">
            <x-admin.card title="Activity" subtitle="Latest storefront events">
                <ul class="space-y-3">
                    @forelse ($activityFeed as $ev)
                        <li class="flex gap-3">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0H6A2.25 2.25 0 013.75 14.25V9" /></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Order #{{ $ev->id }} · {{ ucfirst($ev->status) }}</p>
                                <p class="text-xs text-slate-500">{{ $ev->customer->name ?? 'Customer' }} · {{ $ev->created_at->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('admin.orders.show', $ev) }}" class="shrink-0 text-xs font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400">Open</a>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">No recent activity.</li>
                    @endforelse
                </ul>
            </x-admin.card>

            <x-admin.card title="Top products" subtitle="By units sold (all time)">
                <ul class="space-y-3">
                    @forelse ($topProducts as $tp)
                        @php
                            $img = $tp->image ? asset('storage/' . $tp->image) : asset('images/default_product.png');
                        @endphp
                        <li class="flex items-center gap-3">
                            <img src="{{ $img }}" alt="" class="h-10 w-10 rounded-xl border border-slate-200 object-cover dark:border-slate-700" loading="lazy" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $tp->name }}</p>
                                <p class="text-xs text-slate-500">{{ (int) $tp->units_sold }} sold · ₹{{ number_format((float) $tp->line_revenue, 2) }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">No sales data yet.</li>
                    @endforelse
                </ul>
            </x-admin.card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const Chart = window.Chart;
            const months = @json($months);
            const orderData = @json($orderData);
            const customerData = @json($customerData);
            const revenueData = @json($revenueData);

            const gridColor = document.documentElement.classList.contains('dark') ? 'rgba(148, 163, 184, 0.15)' : 'rgba(15, 23, 42, 0.06)';
            const tickColor = document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b';

            const commonOpts = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: tickColor, font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: tickColor, font: { size: 11 } },
                    },
                },
            };

            new Chart(document.getElementById('ordersChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        data: orderData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    }],
                },
                options: commonOpts,
            });

            new Chart(document.getElementById('customersChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        data: customerData,
                        backgroundColor: 'rgba(14, 165, 233, 0.35)',
                        borderRadius: 8,
                        maxBarThickness: 28,
                    }],
                },
                options: commonOpts,
            });

            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        data: revenueData,
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    }],
                },
                options: {
                    ...commonOpts,
                    scales: {
                        ...commonOpts.scales,
                        y: {
                            ...commonOpts.scales.y,
                            ticks: {
                                ...commonOpts.scales.y.ticks,
                                callback: (value) => '₹' + Number(value).toLocaleString(),
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush
