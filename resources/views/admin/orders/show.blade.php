@extends('layouts.app')

@section('title', 'Order #' . $order->id)

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
        $steps = ['pending', 'processing', 'shipped', 'delivered'];
        $itemsCount = $order->items->sum('quantity');
        $subtotal = $order->items->sum(fn ($i) => $i->price * $i->quantity);
        $stepIndex = array_search($order->status, $steps, true);
        if ($order->status === 'cancelled') {
            $stepIndex = false;
        }
    @endphp

    <x-admin.page-header
        :title="'Order #' . $order->id"
        description="Placed {{ $order->created_at->format('d M Y, h:i A') }}"
        :breadcrumbs="[
            ['label' => 'Orders', 'href' => route('admin.orders.index')],
            ['label' => '#' . $order->id],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('admin.orders.invoice', $order) }}" variant="secondary" size="md">Print</x-admin.button>
            <x-admin.button href="{{ route('admin.orders.invoice-pdf', $order) }}" variant="primary" size="md">Download PDF</x-admin.button>
            <x-admin.button href="{{ route('admin.orders.index') }}" variant="ghost" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card class="mb-6" title="Fulfillment" subtitle="Update lifecycle and track milestones.">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <x-admin.badge :variant="$statusVariant($order->status)" class="text-sm">{{ ucfirst($order->status) }}</x-admin.badge>
                <span class="text-xs text-slate-500 dark:text-slate-400">Updated {{ $order->updated_at->diffForHumans() }}</span>
            </div>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                @csrf
                @method('PUT')
                <select name="status" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white sm:min-w-[11rem]">
                    @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $value)
                        <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
                <x-admin.button type="submit" variant="primary" size="md">Save status</x-admin.button>
            </form>
        </div>

        @if ($stepIndex !== false)
            <div class="mt-8">
                <ol class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    @foreach ($steps as $i => $step)
                        @php $done = $stepIndex !== false && $stepIndex >= $i; @endphp
                        <li class="flex flex-1 items-center gap-3 md:flex-col md:items-center md:text-center">
                            <div class="flex w-full items-center md:flex-col md:gap-2">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $done ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }}">
                                    @if ($done)
                                        ✓
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </span>
                                @if (! $loop->last)
                                    <div class="mx-2 hidden h-0.5 flex-1 rounded-full md:block {{ $done ? 'bg-emerald-400' : 'bg-slate-200 dark:bg-slate-700' }}"></div>
                                @endif
                            </div>
                            <p class="text-sm font-semibold capitalize text-slate-900 dark:text-white">{{ $step }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif
    </x-admin.card>

    <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <x-admin.card title="Order summary" subtitle="Totals for this checkout.">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500 dark:text-slate-400">Line items</dt>
                    <dd class="font-semibold tabular-nums text-slate-900 dark:text-white">{{ $itemsCount }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500 dark:text-slate-400">Subtotal</dt>
                    <dd class="font-semibold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($subtotal, 2) }}</dd>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-3 dark:border-slate-800">
                    <dt class="font-semibold text-slate-800 dark:text-slate-200">Total</dt>
                    <dd class="text-lg font-bold tabular-nums text-violet-600 dark:text-violet-300">₹{{ number_format($order->total_price, 2) }}</dd>
                </div>
            </dl>
        </x-admin.card>

        <x-admin.card title="Shipping" subtitle="Destination on file.">
            @if ($order->address)
                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $order->address->address_line1 }}</p>
                @if ($order->address->address_line2)
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->address->address_line2 }}</p>
                @endif
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $order->address->city }}, {{ $order->address->state }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-300">PIN {{ $order->address->postal_code }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->address->country }}</p>
            @else
                <p class="text-sm text-slate-500">No shipping address on this order.</p>
            @endif
        </x-admin.card>

        <x-admin.card title="Customer" subtitle="Primary contact.">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $order->customer->name }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $order->customer->email }}</p>
            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->customer->mobile }}</p>
            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->customer->location }}</p>
        </x-admin.card>
    </div>

    <x-admin.card title="Line items" subtitle="Products included in this order." :padding="false">
        <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800/95 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3"> </th>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Color</th>
                        <th class="px-5 py-3 text-right">Qty</th>
                        <th class="px-5 py-3 text-right">Price</th>
                        <th class="px-5 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($order->items as $item)
                        @php
                            $rawImage = $item->product->image ?? null;
                            $imageUrl = null;
                            if ($rawImage) {
                                $imageUrl = filter_var($rawImage, FILTER_VALIDATE_URL) ? $rawImage : asset('storage/' . ltrim($rawImage, '/'));
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="" class="h-12 w-12 rounded-xl border border-slate-200 object-cover dark:border-slate-700" />
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-dashed border-slate-200 text-slate-400 dark:border-slate-700">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159M18 6H6" /></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">{{ $item->product->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $item->product->color_name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right tabular-nums text-slate-600 dark:text-slate-300">{{ $item->quantity }}</td>
                            <td class="px-5 py-3.5 text-right tabular-nums text-slate-600 dark:text-slate-300">₹{{ number_format($item->price, 2) }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-white">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection
