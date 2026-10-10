@extends('layouts.app')

@section('title', '429 - Terlalu Banyak Permintaan | ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <p class="text-xs font-bold uppercase tracking-widest text-rose-700 mb-2">Error 429</p>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mb-3">Terlalu Banyak Permintaan</h1>
        <p class="text-sm text-stone-600 mb-8 leading-relaxed">
            Sistem mendeteksi terlalu banyak permintaan berturut-turut demi melindungi keamanan server. Mohon tunggu beberapa saat sebelum mencoba kembali.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs tracking-wide shadow-goldglow transition-all">
                Kembali ke Beranda
            </a>
            <a href="{{ wa_link('Halo Admin, saya mengalami kendala pada sistem katalog undangan.') }}" target="_blank" rel="noopener" class="w-full sm:w-auto px-6 py-3 rounded-full border border-stone-300 hover:border-champagne text-charcoal-700 text-xs font-semibold transition-all">
                Bantuan WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
