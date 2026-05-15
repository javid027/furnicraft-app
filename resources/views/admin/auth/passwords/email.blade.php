@extends('layouts.guest')

@section('title', 'Reset password')

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-8 shadow-xl shadow-slate-900/10 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
        <h1 class="text-center text-xl font-bold text-slate-900 dark:text-white">Reset password</h1>
        <p class="mt-1 text-center text-sm text-slate-500 dark:text-slate-400">We will email you a secure link.</p>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
            @csrf

            <x-admin.field label="Email" for="email" :required="true">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 @error('email') border-red-400 @enderror dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </x-admin.field>

            <x-admin.button type="submit" variant="primary" size="lg" class="w-full justify-center">Send reset link</x-admin.button>

            <p class="text-center text-sm text-slate-500">
                <a href="{{ route('login') }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400">Back to sign in</a>
            </p>
        </form>
    </div>
@endsection
