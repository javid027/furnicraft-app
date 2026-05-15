@extends('layouts.app')

@section('title', 'Edit banner')

@section('content')
    <x-admin.page-header
        title="Edit banner"
        description="Refresh campaign creative or messaging."
        :breadcrumbs="[
            ['label' => 'Banners', 'href' => route('banners.index')],
            ['label' => 'Edit'],
        ]"
    >
        <x-slot:actions>
            <x-admin.button href="{{ route('banners.index') }}" variant="secondary" size="md">Back</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.card>
        @include('admin.banners._form', ['banner' => $banner])
    </x-admin.card>
@endsection
