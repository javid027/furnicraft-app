@extends('layouts.app')

@section('title', isset($banner) ? 'Edit banner' : 'New banner')

@section('content')
    <x-admin.page-header
        :title="isset($banner) ? 'Edit banner' : 'Create banner'"
        description="Upload landscape creatives and toggle storefront visibility."
        :breadcrumbs="[
            ['label' => 'Banners', 'href' => route('banners.index')],
            ['label' => isset($banner) ? 'Edit' : 'Create'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('banners.index') }}" variant="secondary" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card>
        @include('admin.banners._form')
    </x-admin.card>
@endsection
