<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="adminUi()" x-init="init()" @keydown.escape.window="closePanels()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — FurniCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full">
    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"
            x-show="mobileOpen"
            x-transition.opacity
            x-cloak
            @click="mobileOpen = false"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800/80 bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900 text-slate-300 shadow-xl shadow-slate-950/40 transition-all duration-300 ease-out lg:static lg:z-auto lg:shadow-none"
            :class="[
                mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                collapsed ? 'lg:w-20' : 'lg:w-72',
            ]"
        >
            <div class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-800/80 px-4 lg:px-5">
                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-violet-500/30">
                        FC
                    </span>
                    <div class="min-w-0 overflow-hidden transition-all duration-300" :class="collapsed ? 'lg:w-0 lg:opacity-0' : 'lg:opacity-100'">
                        <p class="truncate text-sm font-semibold text-white">FurniCraft</p>
                        <p class="truncate text-xs text-slate-500">Commerce admin</p>
                    </div>
                </a>
                <button
                    type="button"
                    class="hidden rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white lg:inline-flex"
                    @click="toggleCollapsed()"
                    title="Toggle sidebar width"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Main">
                <p class="mb-2 px-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500 transition-opacity" :class="collapsed ? 'lg:opacity-0' : ''">Overview</p>

                @php
                    $link = fn ($active) => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition ' . ($active
                        ? 'bg-white/10 text-white shadow-inner shadow-black/20 ring-1 ring-white/10'
                        : 'text-slate-400 hover:bg-slate-800/60 hover:text-white');
                @endphp

                <a href="{{ route('admin.dashboard') }}" class="{{ $link(request()->routeIs('admin.dashboard')) }}" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 text-violet-400 group-hover:text-violet-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span class="truncate transition-opacity" :class="collapsed ? 'lg:hidden' : ''">Dashboard</span>
                </a>

                <p class="mb-2 mt-6 px-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500 transition-opacity" :class="collapsed ? 'lg:opacity-0' : ''">Catalog</p>

                <a href="{{ route('categories.index') }}" class="{{ $link(request()->routeIs('categories.*')) }}" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm0 5.25h.007v.008H3.75v-.008zm0 5.25h.007v.008H3.75v-.008z" /></svg>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">Categories</span>
                </a>
                <a href="{{ route('products.index') }}" class="{{ $link(request()->routeIs('products.*')) }}" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">Products</span>
                </a>
                <a href="{{ route('banners.index') }}" class="{{ $link(request()->routeIs('banners.*')) }}" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3A1.5 1.5 0 001.5 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">Banners</span>
                </a>

                <p class="mb-2 mt-6 px-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500 transition-opacity" :class="collapsed ? 'lg:opacity-0' : ''">People & sales</p>

                <a href="{{ route('customers.index') }}" class="{{ $link(request()->routeIs('customers.*')) }}" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">Customers</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="{{ $link(request()->routeIs('admin.orders.*')) }} relative" @click="mobileOpen = false">
                    <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974a1.125 1.125 0 011.119 1.007z" /></svg>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">Orders</span>
                    @if (($pendingOrdersCount ?? 0) > 0)
                        <span class="ml-auto inline-flex min-w-[1.25rem] justify-center rounded-full bg-amber-500/20 px-1.5 text-[10px] font-bold text-amber-300 ring-1 ring-amber-500/30" :class="collapsed ? 'lg:absolute lg:right-2 lg:top-2' : ''">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>
            </nav>

            <div class="border-t border-slate-800/80 p-3">
                <div class="flex items-center gap-3 rounded-xl bg-slate-900/50 p-2 ring-1 ring-slate-800/80" :class="collapsed ? 'lg:justify-center' : ''">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(optional($adminUser)->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1 overflow-hidden transition-opacity" :class="collapsed ? 'lg:hidden' : ''">
                        <p class="truncate text-sm font-medium text-white">{{ $adminUser->name ?? 'Admin' }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $adminUser->email ?? '' }}</p>
                    </div>
                </div>
                <button
                    type="button"
                    class="mt-2 flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-slate-400 transition hover:bg-red-950/40 hover:text-red-300"
                    :class="collapsed ? 'lg:justify-center' : ''"
                    @click="logoutOpen = true; notifOpen = false; profileOpen = false"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">Sign out</span>
                </button>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 shadow-sm shadow-slate-900/5 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/90">
                <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button type="button" class="inline-flex rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800" @click="mobileOpen = true" aria-label="Open menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>

                    <div class="relative hidden max-w-md flex-1 md:block" @click.outside="searchOpen = false">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        </span>
                        <input
                            type="search"
                            placeholder="Search orders, products, customers…"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"
                            @focus="searchOpen = true"
                        />
                        <div
                            x-show="searchOpen"
                            x-transition
                            x-cloak
                            class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white p-3 text-sm text-slate-600 shadow-xl dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300"
                        >
                            <p class="px-2 py-1 text-xs font-medium text-slate-400">Quick links</p>
                            <a class="block rounded-lg px-2 py-2 hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('products.index') }}">Products</a>
                            <a class="block rounded-lg px-2 py-2 hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('admin.orders.index') }}">Orders</a>
                            <a class="block rounded-lg px-2 py-2 hover:bg-slate-50 dark:hover:bg-slate-800" href="{{ route('customers.index') }}">Customers</a>
                        </div>
                    </div>

                    <div class="ml-auto flex items-center gap-1 sm:gap-2">
                        <button
                            type="button"
                            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800 dark:hover:bg-slate-800 dark:hover:text-white"
                            @click="toggleDark()"
                            title="Toggle theme"
                        >
                            <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                            <svg x-show="dark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                        </button>

                        <div class="relative" @click.outside="notifOpen = false">
                            <button type="button" class="relative rounded-xl p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" @click="notifOpen = !notifOpen; profileOpen = false">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.113V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3.206 3.206 0 01-3.206-3.206V15.75a3.75 3.75 0 00-7.5 0v.318a3.206 3.206 0 01-3.206 3.206z" /></svg>
                                @if (($pendingOrdersCount ?? 0) > 0)
                                    <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-amber-500 ring-2 ring-white dark:ring-slate-950"></span>
                                @endif
                            </button>
                            <div
                                x-show="notifOpen"
                                x-transition
                                x-cloak
                                class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-2xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-900"
                            >
                                <p class="px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Notifications</p>
                                @if (($pendingOrdersCount ?? 0) > 0)
                                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex gap-3 rounded-xl px-3 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5" /></svg>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-medium text-slate-900 dark:text-white">{{ $pendingOrdersCount }} orders need attention</span>
                                            <span class="text-xs text-slate-500">Pending fulfillment — review queue</span>
                                        </span>
                                    </a>
                                @else
                                    <p class="px-3 py-6 text-center text-sm text-slate-500">You are all caught up.</p>
                                @endif
                            </div>
                        </div>

                        <div class="relative hidden sm:block" @click.outside="profileOpen = false">
                            <button type="button" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-3 text-left shadow-sm hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900" @click="profileOpen = !profileOpen; notifOpen = false">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 text-xs font-bold text-white">{{ strtoupper(substr(optional($adminUser)->name ?? 'A', 0, 1)) }}</span>
                                <span class="max-w-[8rem] truncate text-sm font-medium text-slate-800 dark:text-slate-200">{{ \Illuminate\Support\Str::limit($adminUser->name ?? 'Admin', 18) }}</span>
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            </button>
                            <div
                                x-show="profileOpen"
                                x-transition
                                x-cloak
                                class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-2xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-700 dark:bg-slate-900"
                            >
                                <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $adminUser->name ?? 'Admin' }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $adminUser->email ?? '' }}</p>
                                </div>
                                <button type="button" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30" @click="logoutOpen = true; profileOpen = false">
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <x-admin.flash />
                <div class="fc-content mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Logout confirm modal --}}
    <div
        x-show="logoutOpen"
        x-transition.opacity.duration.200ms
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        @keydown.escape.window="logoutOpen = false"
    >
        <div
            @click.outside="logoutOpen = false"
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900"
            x-show="logoutOpen"
            x-transition
        >
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Sign out?</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">You will need to sign in again to access the admin panel.</p>
            <div class="mt-6 flex justify-end gap-2">
                <x-admin.button type="button" variant="secondary" @click="logoutOpen = false">Cancel</x-admin.button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <x-admin.button type="submit" variant="danger">Sign out</x-admin.button>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
