<x-admin-layout>
    <x-slot:heading>Dashboard Ringkasan</x-slot:heading>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Products -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-subtle flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Produk</p>
                <h3 class="text-2xl font-bold font-serif text-charcoal-900 mt-1">{{ $stats['total_products'] }}</h3>
                <span class="text-xs text-stone-400">Undangan terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-champagne-50 text-champagne-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-subtle flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kategori</p>
                <h3 class="text-2xl font-bold font-serif text-charcoal-900 mt-1">{{ $stats['total_categories'] }}</h3>
                <span class="text-xs text-stone-400">Bronze, Silver, Gold, Diamond</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sage-50 text-sage-dark flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-subtle flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Pesanan (Leads)</p>
                <h3 class="text-2xl font-bold font-serif text-charcoal-900 mt-1">{{ $stats['total_orders'] }}</h3>
                <span class="text-xs text-stone-400">Checkout masuk</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>

        <!-- Members -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200/80 shadow-subtle flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Member</p>
                <h3 class="text-2xl font-bold font-serif text-charcoal-900 mt-1">{{ $stats['total_members'] }}</h3>
                <span class="text-xs text-stone-400">Pengguna terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Quick Action / Status Banner -->
    <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-subtle mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <x-badge color="gold" size="md" class="mb-2">Fase 1 Berhasil Diinisialisasi</x-badge>
                <h2 class="text-2xl font-serif font-bold text-charcoal-900">Selamat Datang di Panel Manajemen Galeria Invitation</h2>
                <p class="text-sm text-stone-600 mt-1 max-w-2xl">
                    Kerangka sistem dasar, autentikasi berbasis role (Admin &amp; Member), model database relasional, dan seeding produk contoh telah siap.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-button href="{{ route('home') }}" variant="outline" size="md">
                    Lihat Katalog Publik
                </x-button>
            </div>
        </div>
    </div>
</x-admin-layout>
