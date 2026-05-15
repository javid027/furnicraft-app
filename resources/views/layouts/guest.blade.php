<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ dark: localStorage.getItem('fc-theme') === 'dark' }" x-init="document.documentElement.classList.toggle('dark', dark)" @keydown.escape.window="$dispatch('close-modal')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'FurniCraft')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#F8FAFC] dark:bg-slate-950">
    <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-violet-400/20 blur-3xl dark:bg-violet-600/20"></div>
            <div class="absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-indigo-400/20 blur-3xl dark:bg-indigo-600/15"></div>
        </div>
        <a href="{{ url('/') }}" class="relative z-10 mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-violet-500/30">FC</span>
            <span class="text-lg font-bold tracking-tight">FurniCraft Admin</span>
        </a>
        <div class="relative z-10 w-full max-w-md">
            @yield('content')
        </div>
    </div>
</body>
</html>
