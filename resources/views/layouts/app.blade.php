<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? setting('site_name', 'Katalog Undangan') }} - {{ setting('site_tagline', 'Spesialis Undangan Pernikahan') }}</title>
    <meta name="description" content="Katalog undangan pernikahan cetak dan digital premium terinspirasi Galeria Invitation.">

    <!-- Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('seo')
</head>
<body class="min-h-full flex flex-col bg-ivory text-charcoal font-sans antialiased selection:bg-champagne-200 selection:text-charcoal-900">

    <!-- 1. STICKY LUXURY NAVBAR -->
    <header class="sticky top-0 z-40 w-full bg-ivory/95 backdrop-blur-md border-b border-champagne-200/60 transition-all duration-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-champagne-500 text-white flex items-center justify-center font-serif text-lg font-bold shadow-sm transition-transform duration-300 group-hover:scale-105">
                            G
                        </div>
                        <div class="flex flex-col">
                            <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight text-charcoal-900 group-hover:text-champagne-600 transition-colors">
                                {{ setting('site_name', 'Galeria Invitation') }}
                            </span>
                            <span class="text-[10px] uppercase font-semibold tracking-widest text-champagne-700 -mt-1 hidden sm:block">
                                Wedding Catalog &amp; Digital
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors hover:text-champagne-600 {{ request()->routeIs('home') ? 'text-champagne-700 font-semibold border-b-2 border-champagne' : 'text-charcoal-700' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog.index') }}" class="text-sm font-medium transition-colors hover:text-champagne-600 {{ request()->routeIs('catalog.*') || request()->is('katalog*') ? 'text-champagne-700 font-semibold border-b-2 border-champagne' : 'text-charcoal-700' }}">
                        Katalog
                    </a>
                    <a href="{{ route('how-to-order') }}" class="text-sm font-medium transition-colors hover:text-champagne-600 {{ request()->routeIs('how-to-order') ? 'text-champagne-700 font-semibold border-b-2 border-champagne' : 'text-charcoal-700' }}">
                        Cara Pesan
                    </a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium transition-colors hover:text-champagne-600 {{ request()->routeIs('contact') ? 'text-champagne-700 font-semibold border-b-2 border-champagne' : 'text-charcoal-700' }}">
                        Kontak
                    </a>
                </nav>

                <!-- Actions: Cart & Auth -->
                <div class="flex items-center gap-4">
                    <!-- Cart Button -->
                    @php
                        $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0;
                    @endphp
                    <a href="{{ url('/cart') }}" class="relative p-2.5 rounded-full text-charcoal-700 hover:text-champagne-600 hover:bg-champagne-50 transition-colors" title="Keranjang Belanja">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-5 h-5 flex items-center justify-center text-[11px] font-bold text-white bg-champagne-600 rounded-full shadow-sm animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login -->
                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-charcoal-800 hover:text-champagne-600 focus:outline-none py-1.5 px-3 rounded-full hover:bg-champagne-50 transition-colors">
                                <span class="w-8 h-8 rounded-full bg-champagne-100 border border-champagne-300 text-champagne-800 flex items-center justify-center font-semibold text-xs">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                                <span class="hidden lg:inline-block max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-elegant border border-stone-200/80 py-2 z-50">
                                <div class="px-4 py-2 border-b border-stone-100">
                                    <p class="text-xs text-stone-500">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-charcoal-900 truncate">{{ auth()->user()->name }}</p>
                                    @if(auth()->user()->isAdmin())
                                        <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold bg-champagne-100 text-champagne-800 rounded-full">
                                            Administrator
                                        </span>
                                    @endif
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-champagne-700 hover:bg-champagne-50 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        Admin Panel
                                    </a>
                                @endif

                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-charcoal-700 hover:bg-stone-50">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Dashboard Member
                                </a>

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-charcoal-700 hover:bg-stone-50">
                                    <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                    Profil Saya
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-charcoal-700 hover:text-champagne-600 px-3 py-2 rounded-full transition-colors">
                                Masuk
                            </a>
                            <x-button href="{{ route('register') }}" variant="primary" size="sm">
                                Daftar
                            </x-button>
                        </div>
                    @endauth

                    <!-- Mobile Menu Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-charcoal-700 hover:text-champagne-600 hover:bg-champagne-50 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-transition.opacity.duration.200ms class="md:hidden border-t border-champagne-200/60 py-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-charcoal-800 hover:bg-champagne-50 hover:text-champagne-600">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-charcoal-800 hover:bg-champagne-50 hover:text-champagne-600">
                    Katalog Undangan
                </a>
                <a href="{{ route('how-to-order') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-charcoal-800 hover:bg-champagne-50 hover:text-champagne-600">
                    Cara Pesan
                </a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-charcoal-800 hover:bg-champagne-50 hover:text-champagne-600">
                    Kontak &amp; Lokasi
                </a>
                @guest
                    <div class="pt-4 border-t border-stone-200 flex flex-col gap-2">
                        <x-button href="{{ route('login') }}" variant="outline" size="md" class="w-full">
                            Masuk ke Akun
                        </x-button>
                        <x-button href="{{ route('register') }}" variant="primary" size="md" class="w-full">
                            Daftar Member
                        </x-button>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <!-- 2. FLASH ALERTS & TOAST CONTAINER -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
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

        @if(session('status'))
            <x-alert type="info" class="mb-4">
                {{ session('status') }}
            </x-alert>
        @endif
    </div>

    <!-- 3. PAGE CONTENT -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- 4. LUXURY FOOTER -->
    <footer class="bg-charcoal text-stone-300 border-t border-stone-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-champagne-500 text-white flex items-center justify-center font-serif text-lg font-bold">
                            G
                        </div>
                        <span class="font-serif text-2xl font-bold text-white tracking-tight">
                            {{ setting('site_name', 'Galeria Invitation') }}
                        </span>
                    </div>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        {{ setting('site_tagline', 'Spesialis pembuatan undangan pernikahan cetak mewah dan undangan digital responsif dengan sentuhan seni estetika kontemporer.') }}
                    </p>
                    <div class="pt-2 flex items-center gap-3">
                        <a href="{{ setting('instagram_url', 'https://instagram.com') }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-stone-800 hover:bg-champagne-600 text-stone-300 hover:text-white flex items-center justify-center transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="{{ wa_link('Halo Admin Galeria, saya ingin bertanya info katalog.') }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-stone-800 hover:bg-emerald-600 text-stone-300 hover:text-white flex items-center justify-center transition-colors">
                            <span class="sr-only">WhatsApp</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Kategori Pilihan -->
                <div>
                    <h3 class="font-serif text-lg font-semibold text-white mb-4">Koleksi Kategori</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/catalog?category=bronze') }}" class="hover:text-champagne-400 transition-colors">Paket Bronze (Hemat &amp; Rapi)</a></li>
                        <li><a href="{{ url('/catalog?category=silver') }}" class="hover:text-champagne-400 transition-colors">Paket Silver (Foil &amp; Kalkir)</a></li>
                        <li><a href="{{ url('/catalog?category=gold') }}" class="hover:text-champagne-400 transition-colors">Paket Gold (Velvet &amp; Wax Seal)</a></li>
                        <li><a href="{{ url('/catalog?category=diamond') }}" class="hover:text-champagne-400 transition-colors">Paket Diamond (Akrilik &amp; Hardcover)</a></li>
                        <li><a href="{{ url('/catalog?type=digital') }}" class="hover:text-champagne-400 transition-colors">Koleksi Digital Web &amp; Video</a></li>
                    </ul>
                </div>

                <!-- Navigasi Bantuan -->
                <div>
                    <h3 class="font-serif text-lg font-semibold text-white mb-4">Panduan Pelanggan</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/how-to-order') }}" class="hover:text-champagne-400 transition-colors">Alur 3 Langkah Pemesanan</a></li>
                        <li><a href="{{ url('/cart') }}" class="hover:text-champagne-400 transition-colors">Keranjang Belanja</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-champagne-400 transition-colors">Konsultasi Desain Kustom</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-champagne-400 transition-colors">Portal Member</a></li>
                    </ul>
                </div>

                <!-- Kontak Admin -->
                <div>
                    <h3 class="font-serif text-lg font-semibold text-white mb-4">Hubungi Admin</h3>
                    <ul class="space-y-3 text-sm text-stone-400">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-champagne-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>WhatsApp: <strong class="text-white">+{{ clean_phone_number(setting('whatsapp_number', '6281234567890')) }}</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-champagne-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>{{ setting('contact_email', 'halo@katalogundangan.com') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-champagne-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jakarta &amp; Pengiriman ke Seluruh Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-stone-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500">
                <p>&copy; {{ date('Y') }} {{ setting('site_name', 'Galeria Invitation') }}. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0">Semua pesanan diproses langsung via WhatsApp resmi.</p>
            </div>
        </div>
    </footer>

    <!-- 5. FLOATING WHATSAPP BUTTON (ALWAYS ACCESSIBLE) -->
    @php
        $defaultConsultationMsg = setting('consultation_message', 'Halo Admin, saya ingin konsultasi mengenai undangan pernikahan.');
        $waUrl = wa_link($defaultConsultationMsg);
    @endphp
    <div class="fixed bottom-6 right-6 z-50 group">
        <!-- Floating Label on Hover -->
        <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-charcoal-900 text-white text-xs font-medium py-1.5 px-3 rounded-full shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none hidden sm:block">
            Konsultasi WhatsApp Admin
        </div>

        <!-- Pulse Ring Animation -->
        <span class="absolute -inset-1 rounded-full bg-emerald-400 opacity-60 animate-ping"></span>

        <!-- WhatsApp Circular Button -->
        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="relative w-14 h-14 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white rounded-full flex items-center justify-center shadow-goldglow transition-all duration-300" title="Konsultasi via WhatsApp">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
            </svg>
        </a>
    </div>

    @stack('scripts')
</body>
</html>
