@extends('layouts.app')

@section('title', '403 - Akses Dilarang | ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <p class="text-xs font-bold uppercase tracking-widest text-amber-700 mb-2">Error 403</p>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mb-3">Akses Dilarang</h1>
        <p class="text-sm text-stone-600 mb-8 leading-relaxed">
            Anda tidak memiliki hak akses atau izin untuk membuka halaman ini. Silakan kembali ke beranda atau masuk menggunakan akun yang berwenang.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs tracking-wide shadow-goldglow transition-all">
                Kembali ke Beranda
            </a>
            @guest
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 rounded-full border border-stone-300 hover:border-champagne text-charcoal-700 text-xs font-semibold transition-all">
                    Masuk Akun
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection
