@extends('layouts.guest')

@section('title', 'New password')

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-8 shadow-xl shadow-slate-900/10 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
        <h1 class="text-center text-xl font-bold text-slate-900 dark:text-white">Choose a new password</h1>
        <p class="mt-1 text-center text-sm text-slate-500 dark:text-slate-400">Use at least 8 characters.</p>

        <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <x-admin.field label="Email" for="email" :required="true">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ $email ?? old('email') }}"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 @error('email') border-red-400 @enderror dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </x-admin.field>

            <x-admin.field label="New password" for="password" :required="true">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 @error('password') border-red-400 @enderror dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </x-admin.field>

            <x-admin.field label="Confirm password" for="password_confirmation" :required="true">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
            </x-admin.field>

            <x-admin.button type="submit" variant="primary" size="lg" class="w-full justify-center">Update password</x-admin.button>
        </form>
    </div>
@endsection
