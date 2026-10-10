@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan | ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-champagne-50 border border-champagne-200 flex items-center justify-center text-champagne-600 mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-xs font-bold uppercase tracking-widest text-champagne-700 mb-2">Error 404</p>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-stone-600 mb-8 leading-relaxed">
            Maaf, halaman undangan atau menu yang Anda tuju tidak ditemukan atau tautan telah berpindah alamat.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs tracking-wide shadow-goldglow transition-all">
                Kembali ke Beranda
            </a>
            <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-full border border-stone-300 hover:border-champagne text-charcoal-700 text-xs font-semibold transition-all">
                Jelajahi Katalog
            </a>
        </div>
    </div>
</div>
@endsection
