@extends('layouts.app')

@section('title', 'New customer')

@section('content')
    <x-admin.page-header
        title="New customer"
        description="Create a profile and optional avatar."
        :breadcrumbs="[
            ['label' => 'Customers', 'href' => route('customers.index')],
            ['label' => 'Create'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('customers.index') }}" variant="secondary" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('admin.customers._form', ['customer' => new \App\Models\Customer()])
    </form>
@endsection
