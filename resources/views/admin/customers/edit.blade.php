@extends('layouts.app')

@section('title', 'Edit customer')

@section('content')
    <x-admin.page-header
        title="Edit customer"
        description="Refresh contact info and access status."
        :breadcrumbs="[
            ['label' => 'Customers', 'href' => route('customers.index')],
            ['label' => $customer->name],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('customers.index') }}" variant="secondary" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @include('admin.customers._form')
    </form>
@endsection
