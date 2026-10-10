<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Katalog Undangan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-stone-100 text-charcoal font-sans antialiased flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-charcoal/60 md:hidden"></div>

    <!-- 1. ADMIN SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 z-50 w-64 bg-charcoal-900 text-stone-300 flex flex-col transition-transform duration-200 ease-in-out md:static md:translate-x-0 shadow-xl border-r border-stone-800">
        
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-stone-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-champagne-500 text-white flex items-center justify-center font-serif text-lg font-bold">
                    G
                </div>
                <div class="flex flex-col">
                    <span class="font-serif text-lg font-bold text-white tracking-tight leading-tight">Admin Panel</span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-champagne-400">Galeria Invitation</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-stone-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-stone-500 mb-2">Manajemen Utama</p>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <!-- Produk -->
            <a href="{{ url('/admin/products') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/products*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Produk Undangan
            </a>

            <!-- Kategori -->
            <a href="{{ url('/admin/categories') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/categories*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori
            </a>

            <!-- Promo & Diskon -->
            <a href="{{ url('/admin/promos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/promos*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kupon &amp; Promo
            </a>

            <!-- Banner Promosi -->
            <a href="{{ url('/admin/banners') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/banners*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Banner Slider
            </a>

            <!-- Pesanan / Leads -->
            <a href="{{ url('/admin/orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/orders*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Pesanan (Leads)
            </a>

            <!-- Member / Pelanggan -->
            <a href="{{ url('/admin/members') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/members*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Daftar Member
            </a>

            <!-- Pengaturan -->
            <a href="{{ url('/admin/settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('admin/settings*') ? 'bg-champagne-700 text-white font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                Pengaturan Web
            </a>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-stone-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-champagne-400 hover:text-white hover:bg-stone-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Lihat Website Publik
            </a>
        </div>
    </aside>

    <!-- 2. MAIN CONTAINER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-stone-200 px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden text-stone-600 hover:text-charcoal focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div>
                    <h1 class="text-xl font-bold font-serif text-charcoal-900 leading-tight">
                        {{ $heading ?? 'Dashboard Admin' }}
                    </h1>
                    <p class="text-xs text-stone-500">Panel Kontrol Katalog &amp; Pesanan Galeria</p>
                </div>
            </div>

            <!-- Topbar User Info -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-charcoal-900 leading-none">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <span class="text-[10px] uppercase font-bold text-champagne-700 bg-champagne-50 px-2 py-0.5 rounded-full inline-block mt-1">
                        Administrator
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-stone-500 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="px-4 sm:px-8 pt-6">
            @if(session('success'))
                <x-alert type="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="error" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif
        </div>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-8 pb-12 pt-2">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
