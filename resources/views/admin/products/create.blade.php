@extends('layouts.app')

@section('title', 'New product')

@section('content')
    <x-admin.page-header
        title="Create product"
        description="Add inventory with consistent pricing and media."
        :breadcrumbs="[
            ['label' => 'Products', 'href' => route('products.index')],
            ['label' => 'Create'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('products.index') }}" variant="secondary" size="md">Back to list</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @include('admin.products._form', ['submitLabel' => 'Create product', 'product' => null])
    </form>
@endsection
