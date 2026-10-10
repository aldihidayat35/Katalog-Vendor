@extends('layouts.app')

@section('title', '500 - Kendala Server | ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-stone-100 border border-stone-200 flex items-center justify-center text-charcoal-700 mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <p class="text-xs font-bold uppercase tracking-widest text-charcoal-500 mb-2">Error 500</p>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mb-3">Terjadi Kendala Teknis</h1>
        <p class="text-sm text-stone-600 mb-8 leading-relaxed">
            Sistem kami sedang mengalami kendala sementara saat memproses permintaan Anda. Tim kami telah menerima notifikasi dan segera memperbaikinya.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs tracking-wide shadow-goldglow transition-all">
                Kembali ke Beranda
            </a>
            <a href="{{ wa_link('Halo Admin, saya mengalami error saat mengakses sistem katalog undangan.') }}" target="_blank" rel="noopener" class="w-full sm:w-auto px-6 py-3 rounded-full border border-stone-300 hover:border-champagne text-charcoal-700 text-xs font-semibold transition-all">
                Hubungi Kami via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
