@extends('layouts.app')

@section('title', 'Edit product')

@section('content')
    <x-admin.page-header
        title="Edit product"
        description="Update merchandising details and stock."
        :breadcrumbs="[
            ['label' => 'Products', 'href' => route('products.index')],
            ['label' => $product->name],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('products.index') }}" variant="secondary" size="md">Back to list</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @method('PUT')
        @include('admin.products._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
