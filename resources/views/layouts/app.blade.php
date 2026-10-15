<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = setting('site_name', 'Galeria Invitation');
        $siteTagline = setting('site_tagline', 'Spesialis Undangan Pernikahan Mewah & Elegan');
        $defaultDesc = setting('seo_meta_description', 'Vendor percetakan dan spesialis undangan pernikahan mewah, elegan, dan eksklusif. Menyediakan undangan cetak fisik berkualitas premium serta undangan digital interaktif untuk pengiriman ke seluruh Indonesia.');
        $defaultKeywords = setting('seo_meta_keywords', 'undangan pernikahan, vendor undangan pernikahan, cetak undangan mewah, undangan elegan, undangan pernikahan eksklusif, undangan online, cetak undangan pernikahan indonesia');
        $googleVerification = setting('google_site_verification');

        $pageTitle = isset($title) && !empty($title) ? "{$title} - {$siteName}" : "{$siteName} — {$siteTagline}";
        $pageDesc = $description ?? $defaultDesc;
        $pageKeywords = $keywords ?? $defaultKeywords;
        $pageUrl = $canonicalUrl ?? url()->current();
        $pageImage = $ogImage ?? (setting('site_logo') ? site_logo_url() : asset('images/placeholder-product.svg'));
        $pageType = $ogType ?? 'website';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $pageUrl }}">
    <link rel="icon" href="{{ site_favicon_url() }}">

    @if (!empty($googleVerification))
        <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $pageType }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $pageUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    @php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
            'logo' => site_logo_url(),
            'description' => $defaultDesc,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+' . clean_phone_number(setting('whatsapp_number', '6281234567890')),
                'contactType' => 'customer service',
                'areaServed' => 'ID',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
        ];
        if (setting('instagram_url')) {
            $organizationSchema['sameAs'] = [setting('instagram_url')];
        }
    @endphp
    <!-- Schema.org Organization (Global) -->
    <script type="application/ld+json">
    {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>

    @stack('schema')

    <!-- Fonts: DM Sans & Playfair Display (Matching Landing Page) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('seo')

    <style>
        :root {
            --bronze: #935006;
            --bronze-hover: #ad641c;
            --ink: #37271f;
            --muted: #706a66;
            --surface: #ffffff;
            --soft: #f3f2f0;
            --line: #eeece9;
        }
        body {
            background: radial-gradient(ellipse at 50% -6%, #e8e6e4 0%, #f3f2f1 65%);
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--ink);
        }
        .serif { font-family: 'Playfair Display', Georgia, serif; }

        /* Navigation Matching Landing Page */
        .nav-shell {
            max-width: 1224px;
            width: calc(100% - 56px);
            margin: auto;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 28px;
        }
        .logo { display: flex; gap: 8px; align-items: center; text-decoration: none; color: #211c19; }
        .logo-mark { width: 24px; height: 30px; color: #8e4d06; }
        .logo-img { height: 30px; width: auto; max-width: 44px; object-fit: contain; border-radius: 6px; }
        .logo-word { font-size: 22px; font-family: 'Playfair Display', Georgia, serif; font-weight: 700; letter-spacing: -.04em; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { text-decoration: none; color: #3b3531; font-size: 12px; font-weight: 600; white-space: nowrap; transition: color .2s; }
        .nav-links a:hover, .nav-links a.active { color: #8b4902; }
        .nav-links a.active:after { content: ''; height: 2px; background: #9d5913; display: block; margin-top: 4px; border-radius: 1px; }

        .nav-tools { display: flex; align-items: center; gap: 10px; }
        .nav-icon-btn {
            height: 34px;
            width: 34px;
            background: #f7f7f7;
            border: 1px solid #e9e8e6;
            border-radius: 50%;
            color: #514c47;
            display: grid;
            place-items: center;
            transition: all .2s;
            position: relative;
            text-decoration: none;
            cursor: pointer;
        }
        .nav-icon-btn:hover { background: #efece8; color: #8e4d06; }
        .nav-icon-btn svg { width: 17px; height: 17px; }
        .badge-count {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #975205;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: grid;
            place-items: center;
        }

        .nav-btn {
            height: 36px;
            min-width: 80px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #e8e7e7;
            color: #39332e;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s;
            border: 0;
            cursor: pointer;
        }
        .nav-btn.primary {
            background: #975205;
            color: white;
            min-width: 90px;
            box-shadow: 0 4px 10px #5b330a20;
        }
        .nav-btn:hover { filter: brightness(.95); }

        .mobile-menu-button {
            display: none;
            border: 1px solid #e6e3e0;
            border-radius: 9px;
            background: white;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .mobile-nav { display: none; }

        @media(max-width: 768px) {
            .nav-shell { width: calc(100% - 32px); height: 56px; }
            .nav-links, .nav-tools .nav-btn, .nav-tools .user-dropdown-wrap { display: none; }
            .mobile-menu-button { display: flex; }
            .mobile-nav {
                display: block;
                position: absolute;
                top: 60px;
                left: 16px;
                right: 16px;
                background: #fff;
                border: 1px solid #ece8e2;
                border-radius: 13px;
                padding: 12px;
                z-index: 70;
                box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            }
            .mobile-nav[hidden] { display: none; }
            .mobile-nav a {
                display: block;
                padding: 10px 12px;
                color: #453930;
                font-size: 13px;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
            }
            .mobile-nav a:hover { background: #f7f3ee; color: #8e4d06; }
            .mobile-auth { border-top: 1px solid #eeece9; margin-top: 8px; padding-top: 8px; display: flex; gap: 8px; }
            .mobile-auth a { flex: 1; text-align: center; }
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans antialiased selection:bg-[#f2e6d8] selection:text-[#37271f]">

    <!-- Global SVG Icons -->
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden">
        <symbol id="i-logo" viewBox="0 0 24 30"><path d="M10.8 12.7C7 10.3 4.5 7.2 4.3 3.6c5.7-.5 9 2.3 9.1 7.1L10.8 12.7Zm2.6-2c-.2-4.5 2.2-7.9 7.2-8.8.3 4.6-1.8 8.2-5.7 9.6l-1.5-.8ZM10.4 16c-4-2-7.5-.7-9 2.5C-.7 23 3.8 26.2 9 25.4l1.4-9.4Zm5.2-2c5.2.7 7.9 4.1 7 8.7-1.1 5-6.6 6.5-10.5 3.4l3.5-12.1Z" fill="currentColor"/><path d="M12.4 13.4v13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></symbol>
        <symbol id="i-search" viewBox="0 0 24 24"><circle cx="10.6" cy="10.6" r="7.1" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="m16 16 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
        <symbol id="i-cart" viewBox="0 0 24 24"><path d="M2 3h3l2.1 13h12l2.4-9H6M8 21h.1M18 21h.1" stroke="currentColor" stroke-width="1.7" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
        <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.75" fill="none"/><path d="M5 21v-2a7 7 0 0 1 14 0v2" stroke="currentColor" stroke-width="1.75" fill="none"/></symbol>
        <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    </svg>

    <!-- 1. LANDING-PAGE MATCHED NAVBAR -->
    <header class="sticky top-0 z-40 w-full bg-[#f4f2f0]/95 backdrop-blur-md border-b border-[#ece8e2] transition-all duration-200"
            x-data="{ mobileNavOpen: false, count: {{ (int) ($cartCount ?? (auth()->check() ? auth()->user()->carts()->sum('quantity') : 0)) }} }"
            @cart-updated.window="count = $event.detail.count">
        <nav class="nav-shell" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="logo">
                @if(site_logo_url())
                    <img src="{{ site_logo_url() }}" alt="{{ setting('site_name', 'Undangin') }}" class="logo-img">
                @else
                    <svg class="logo-mark"><use href="#i-logo"/></svg>
                @endif
                <span class="logo-word">{{ setting('site_name', 'Undangin') }}</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('home') }}#template">Koleksi Undangan</a>
                <a href="{{ route('home') }}#kategori">Kategori</a>
                <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') || request()->is('katalog*') ? 'active' : '' }}">Katalog Lengkap</a>
                <a href="{{ route('how-to-order') }}" class="{{ request()->routeIs('how-to-order') ? 'active' : '' }}">Cara Pesan</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
            </div>

            <div class="nav-tools">
                @if(request()->routeIs('catalog.*') || request()->is('katalog*'))
                    <button type="button" onclick="document.querySelector('input[name=q]')?.focus();" class="nav-icon-btn" title="Cari undangan cetak, digital..." aria-label="Cari undangan">
                        <svg><use href="#i-search"/></svg>
                    </button>
                @else
                    <a href="{{ route('catalog.index') }}" class="nav-icon-btn" title="Cari undangan cetak, digital..." aria-label="Cari undangan">
                        <svg><use href="#i-search"/></svg>
                    </a>
                @endif

                <!-- Keranjang Belanja -->
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang Belanja">
                    <svg><use href="#i-cart"/></svg>
                    <span x-show="count > 0" x-text="count" class="badge-count" style="display: none;"></span>
                </a>

                @auth
                    <!-- Auth Actions -->
                    <a href="{{ route('order.index') }}" class="nav-btn" title="Pesanan Saya">
                        Pesanan
                    </a>

                    <!-- User Account Dropdown -->
                    <div class="relative user-dropdown-wrap" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="nav-btn primary flex items-center gap-1.5" title="Profil Akun">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-5 h-5 rounded-full object-cover">
                            @endif
                            <span>Akun</span>
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-[#ece8e2] py-2 z-50 text-left" style="display: none;">
                            <div class="px-4 py-2.5 border-b border-stone-100 flex items-center gap-2.5">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-stone-200 shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-[#f3ede6] text-[#935006] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-stone-800 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-stone-500 truncate">{{ auth()->user()->username ? '@' . auth()->user()->username : auth()->user()->email }}</p>
                                    @if(auth()->user()->isAdmin())
                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-[9px] font-semibold bg-[#f5ede4] text-[#935006] rounded-full">
                                            Administrator
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-[#935006] hover:bg-[#faf8f5] font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Admin Panel
                                </a>
                            @endif

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Dashboard Member
                            </a>

                            <a href="{{ route('order.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Pesanan Saya
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 hover:bg-[#faf8f5]">
                                <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                Profil Saya
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold">
                                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn primary">Daftar</a>
                @endauth

                <button @click="mobileNavOpen = !mobileNavOpen" class="mobile-menu-button" aria-expanded="false" aria-label="Buka menu">
                    <svg width="20" height="20"><use href="#i-menu"/></svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Drawer -->
        <div x-show="mobileNavOpen" x-transition.opacity class="mobile-nav" style="display: none;">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('home') }}#template">Koleksi Undangan</a>
            <a href="{{ route('home') }}#kategori">Kategori Acara</a>
            <a href="{{ route('catalog.index') }}">Katalog Lengkap</a>
            <a href="{{ route('how-to-order') }}">Cara Pesan</a>
            <a href="{{ route('contact') }}">Kontak Kami</a>
            <div class="mobile-auth">
                @auth
                    <a href="{{ route('cart.index') }}" class="nav-btn">Keranjang (<span x-text="count"></span>)</a>
                    <a href="{{ route('order.index') }}" class="nav-btn primary">Pesanan Saya</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn primary">Daftar</a>
                @endauth
            </div>
            @auth
                <div class="pt-2 mt-2 border-t border-[#ece8e2] flex flex-col gap-1 text-xs">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-[#935006] font-semibold py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Admin Panel</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Dashboard Member</a>
                    <a href="{{ route('profile.edit') }}" class="py-1.5 px-3 rounded-lg hover:bg-[#faf8f5]">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-3 text-rose-600 font-semibold rounded-lg hover:bg-rose-50">Keluar</button>
                    </form>
                </div>
            @endauth
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
        @if(isset($slot) && !empty((string)$slot))
            {{ $slot }}
        @endif
        @yield('content')
    </main>

    <!-- 4. LUXURY FOOTER -->
    <footer class="bg-charcoal text-stone-300 border-t border-stone-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        @if(site_logo_url())
                            <img src="{{ site_logo_url() }}" alt="{{ setting('site_name', 'Undangin') }}" class="w-9 h-9 rounded-xl object-contain bg-white/10 p-1">
                        @else
                            <div class="w-9 h-9 rounded-full bg-champagne-500 text-white flex items-center justify-center font-serif text-lg font-bold">
                                {{ substr(setting('site_name', 'U'), 0, 1) }}
                            </div>
                        @endif
                        <span class="font-serif text-2xl font-bold text-white tracking-tight">
                            {{ setting('site_name', 'Undangin') }}
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
                <p>&copy; {{ date('Y') }} {{ setting('site_name', 'Undangin') }}. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="mt-2 sm:mt-0">Semua pesanan diproses langsung via WhatsApp resmi.</p>
            </div>
        </div>
    </footer>

    <!-- 5. FLOATING WHATSAPP BUTTON (ALWAYS ACCESSIBLE) -->
    @php
        $defaultConsultationMsg = setting('consultation_message', 'Halo Admin, saya ingin konsultasi mengenai undangan pernikahan.');
        $waUrl = wa_link($defaultConsultationMsg);
    @endphp
    <div class="fixed bottom-6 right-6 z-50 group {{ request()->routeIs('product.show') ? 'hidden sm:block' : '' }}">
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
