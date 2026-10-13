<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site_name', 'Undangin') }}</title>
    <link rel="icon" href="{{ site_favicon_url() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-ivory text-charcoal font-sans antialiased">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ route('home') }}" class="inline-flex flex-col items-center group">
            @if(site_logo_url())
                <img src="{{ site_logo_url() }}" alt="{{ setting('site_name', 'Undangin') }}" class="w-14 h-14 rounded-2xl object-contain shadow-md mb-3 group-hover:scale-105 transition-transform bg-white/60 p-1 border border-stone-200/50">
            @else
                <div class="w-14 h-14 rounded-full bg-champagne text-white flex items-center justify-center font-serif text-2xl font-bold shadow-md transition-transform group-hover:scale-105 mb-3">
                    {{ substr(setting('site_name', 'U'), 0, 1) }}
                </div>
            @endif
            <h1 class="font-serif text-3xl font-bold text-charcoal-900 tracking-tight">
                {{ setting('site_name', 'Undangin') }}
            </h1>
            <p class="text-xs uppercase tracking-widest text-champagne-700 font-semibold mt-1">
                {{ setting('site_tagline', 'Katalog Undangan Eksklusif') }}
            </p>
        </a>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-elegant rounded-3xl border border-stone-200/80">
            {{ $slot }}
        </div>
        <p class="mt-6 text-center text-xs text-stone-500">
            <a href="{{ route('home') }}" class="hover:text-champagne-600 transition-colors font-medium">
                &larr; Kembali ke Beranda
            </a>
        </p>
    </div>
</body>
</html>
