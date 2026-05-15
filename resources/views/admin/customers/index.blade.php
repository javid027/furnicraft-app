@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <x-admin.page-header
        title="Customers"
        description="Keep profiles accurate and monitor activation health."
        :breadcrumbs="[['label' => 'People', 'href' => route('customers.index')], ['label' => 'Customers']]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('customers.create') }}" variant="primary" size="md">Add customer</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card title="Search" subtitle="Find by name, mobile number, or city." class="mb-6">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <x-admin.field label="Query" for="search">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Name, mobile, or location"
                            class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                        />
                    </div>
                </x-admin.field>
            </div>
            <div class="flex gap-2">
                <x-admin.button type="submit" variant="primary">Apply</x-admin.button>
                <x-admin.button href="{{ route('customers.index') }}" variant="secondary">Reset</x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card :padding="false">
        <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-full text-left text-sm">
                <thead class="sticky top-0 z-10 bg-slate-50/95 text-xs font-semibold uppercase tracking-wide text-slate-500 backdrop-blur dark:bg-slate-800/95 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3"> </th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Mobile</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Location</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($customers as $customer)
                        <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                @if ($customer->user_image)
                                    <img src="{{ asset('storage/' . $customer->user_image) }}" alt="" class="h-10 w-10 rounded-full border border-slate-200 object-cover dark:border-slate-700" />
                                @else
                                    <img src="{{ asset('storage/images/default-avatar.png') }}" alt="" class="h-10 w-10 rounded-full border border-slate-200 opacity-60 dark:border-slate-700" />
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="px-5 py-3.5 tabular-nums text-slate-600 dark:text-slate-300">{{ $customer->mobile ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $customer->email }}</td>
                            <td class="px-5 py-3.5">
                                @if ($customer->is_active)
                                    <x-admin.badge variant="success">Active</x-admin.badge>
                                @else
                                    <x-admin.badge variant="neutral">Inactive</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $customer->location ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex justify-end gap-2">
                                    <x-admin.button href="{{ route('customers.edit', $customer) }}" variant="ghost" size="sm">Edit</x-admin.button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" variant="danger" size="sm">Delete</x-admin.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-sm text-slate-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection
