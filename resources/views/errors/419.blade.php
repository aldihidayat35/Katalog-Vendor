@extends('layouts.app')

@section('title', '419 - Sesi Kedaluwarsa | ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-xs font-bold uppercase tracking-widest text-blue-700 mb-2">Error 419</p>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mb-3">Sesi Kedaluwarsa</h1>
        <p class="text-sm text-stone-600 mb-8 leading-relaxed">
            Halaman formulir telah tidak aktif dalam beberapa waktu demi keamanan. Silakan muat ulang halaman ini dan kirimkan kembali.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="w-full sm:w-auto px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs tracking-wide shadow-goldglow transition-all">
                Muat Ulang Halaman
            </button>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-full border border-stone-300 hover:border-champagne text-charcoal-700 text-xs font-semibold transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
