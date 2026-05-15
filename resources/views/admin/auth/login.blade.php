@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-8 shadow-xl shadow-slate-900/10 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
        <h1 class="text-center text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Welcome back</h1>
        <p class="mt-1 text-center text-sm text-slate-500 dark:text-slate-400">Sign in to manage your storefront.</p>

        @if ($errors->has('email') && !$errors->has('password'))
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-500/30 dark:bg-red-950/40 dark:text-red-200" role="alert">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Email') }}</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    placeholder="you@company.com"
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between gap-2">
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('Password') }}</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400">{{ __('Forgot password?') }}</a>
                </div>

                <div class="relative" x-data="{ show: false }">
                    <input
                        id="password"
                        :type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-12 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 @error('password') border-red-400 @enderror dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                    />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                        @click="show = !show"
                        tabindex="-1"
                    >
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /></svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.524 10.524 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L12 12" /></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30" {{ old('remember') ? 'checked' : '' }} />
                {{ __('Remember me') }}
            </label>

            <x-admin.button type="submit" variant="primary" size="lg" class="w-full justify-center shadow-lg shadow-violet-500/25">
                {{ __('Sign in') }}
            </x-admin.button>
        </form>
    </div>
@endsection
