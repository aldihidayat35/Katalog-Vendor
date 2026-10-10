<x-app-layout>
    <!-- 1. BANNER PROMOSI (SWIPER CAROUSEL) -->
    <section class="relative bg-charcoal text-white overflow-hidden" x-data="{
        initSwiper() {
            if (window.Swiper) {
                new window.Swiper(this.$refs.bannerSwiper, {
                    loop: true,
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    effect: 'fade',
                    fadeEffect: { crossFade: true }
                });
            }
        }
    }" x-init="initSwiper()">
        <div class="swiper" x-ref="bannerSwiper">
            <div class="swiper-wrapper">
                @forelse($banners as $banner)
                    <div class="swiper-slide relative min-h-[460px] sm:min-h-[580px] flex items-center">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover opacity-65">
                        <div class="absolute inset-0 bg-gradient-to-r from-charcoal-900/95 via-charcoal-900/75 to-transparent"></div>
                        
                        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
                            <div class="max-w-2xl space-y-5">
                                <span class="inline-block px-3.5 py-1 text-xs font-bold tracking-widest uppercase rounded-full bg-champagne-500/20 text-champagne-300 border border-champagne-500/40">
                                    Galeria Invitation
                                </span>
                                
                                @if($loop->first)
                                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight">
                                        {{ $banner->title }}
                                    </h1>
                                @else
                                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight">
                                        {{ $banner->title }}
                                    </h2>
                                @endif
                                
                                @if($banner->subtitle)
                                    <p class="text-base sm:text-lg text-stone-300 leading-relaxed max-w-xl">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                                
                                <div class="pt-4 flex flex-wrap items-center gap-4">
                                    <x-button href="{{ $banner->link_url ?? route('catalog.index') }}" variant="primary" size="lg">
                                        Lihat Koleksi &rarr;
                                    </x-button>
                                    <x-button href="{{ wa_link(setting('consultation_message', 'Halo Admin Galeria, saya ingin konsultasi pemesanan undangan.')) }}" target="_blank" rel="noopener" variant="outline" size="lg" class="border-stone-400 text-stone-200 hover:bg-white/10 hover:text-white">
                                        Konsultasi WhatsApp
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="relative min-h-[460px] flex items-center justify-center text-center p-12">
                        <div class="max-w-xl space-y-4">
                            <h1 class="text-4xl font-serif font-bold text-white">Momen Indah Anda Dimulai Dari Sini</h1>
                            <p class="text-stone-300">Temukan koleksi undangan cetak dan digital terbaik untuk hari bahagia Anda.</p>
                            <x-button href="{{ route('catalog.index') }}" variant="primary" size="lg">
                                Buka Katalog
                            </x-button>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- Swiper Controls -->
            <div class="swiper-pagination !bottom-6"></div>
        </div>
    </section>

    <!-- 2. "3 LANGKAH MUDAH" (DENGAN HEROICONS & STEP NUMBER) -->
    <section class="py-16 sm:py-24 bg-white border-b border-champagne-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title
                tag="ALUR PEMESANAN PRAKTIS"
                title="3 Langkah Mudah Menuju Undangan Impian"
                description="Pesan undangan pernikahan Anda secara efisien dan personal langsung terhubung dengan admin profesional kami."
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-10">
                <!-- Step 1: Pilih Desain -->
                <div class="relative p-8 rounded-3xl bg-ivory-100 border border-champagne-200/80 hover:shadow-elegant transition-all duration-300 text-center flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-champagne-100 text-champagne-700 border border-champagne-300 flex items-center justify-center shadow-sm mb-6 group-hover:bg-champagne-500 group-hover:text-white transition-colors">
                        <!-- Heroicon: Sparkles / ViewGrid -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700 mb-1">Langkah 1</span>
                    <h3 class="text-xl font-serif font-bold text-charcoal-900 mb-2">Pilih Desain Favorit</h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Jelajahi koleksi undangan cetak mewah atau undangan digital interaktif dari ragam kategori Bronze hingga Diamond.
                    </p>
                </div>

                <!-- Step 2: Isi Detail & Checkout -->
                <div class="relative p-8 rounded-3xl bg-ivory-100 border border-champagne-200/80 hover:shadow-elegant transition-all duration-300 text-center flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-champagne-100 text-champagne-700 border border-champagne-300 flex items-center justify-center shadow-sm mb-6 group-hover:bg-champagne-500 group-hover:text-white transition-colors">
                        <!-- Heroicon: ShoppingCart / ClipboardDocumentCheck -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700 mb-1">Langkah 2</span>
                    <h3 class="text-xl font-serif font-bold text-charcoal-900 mb-2">Isi Detail &amp; Checkout</h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Tentukan jumlah pesanan, pilih opsi DP atau Full Payment, dan masukkan data mempelai serta alamat pengiriman.
                    </p>
                </div>

                <!-- Step 3: Konfirmasi via WhatsApp -->
                <div class="relative p-8 rounded-3xl bg-ivory-100 border border-champagne-200/80 hover:shadow-elegant transition-all duration-300 text-center flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-champagne-100 text-champagne-700 border border-champagne-300 flex items-center justify-center shadow-sm mb-6 group-hover:bg-champagne-500 group-hover:text-white transition-colors">
                        <!-- Heroicon: ChatBubbleLeftRight -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700 mb-1">Langkah 3</span>
                    <h3 class="text-xl font-serif font-bold text-charcoal-900 mb-2">Konfirmasi via WhatsApp</h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Sistem otomatis menyusun rincian pesanan rapi ke WhatsApp Admin untuk validasi draft, revisi, dan proses produksi.
                    </p>
                </div>
            </div>

            <!-- 3. TOMBOL KONSULTASI -->
            <div class="mt-14 text-center">
                <x-button
                    href="{{ wa_link(setting('consultation_message', 'Halo Admin Galeria, saya ingin konsultasi mengenai pembuatan undangan pernikahan.')) }}"
                    target="_blank"
                    rel="noopener"
                    variant="whatsapp"
                    size="lg"
                    class="shadow-goldglow"
                >
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                    Konsultasi Gratis via WhatsApp
                </x-button>
                <p class="text-xs text-stone-400 mt-2">Respon cepat setiap hari pukul 09.00 - 21.00 WIB</p>
            </div>
        </div>
    </section>

    <!-- 4. HIGHLIGHT KATEGORI PRODUK -->
    <section class="py-16 sm:py-24 bg-ivory">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title
                tag="PILIHAN KASTA DESAIN"
                title="Koleksi Kategori Undangan"
                description="Pilihan paket berkelas yang dirancang proporsional untuk beragam kebutuhan dan konsep resepsi pernikahan Anda."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. PRODUK DENGAN 2 TAB ALPINE ("Terbaru" & "Unggulan") -->
    <section class="py-16 sm:py-24 bg-white border-t border-champagne-100" x-data="{ tab: 'latest' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                <div>
                    <span class="text-xs uppercase font-semibold tracking-widest text-champagne-600 mb-2 block">
                        KOLEKSI PILIHAN
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-serif text-charcoal-900 leading-tight">
                        Desain Undangan Spesial
                    </h2>
                </div>

                <!-- Tab Buttons -->
                <div class="flex items-center gap-2 p-1.5 bg-ivory-200 rounded-full border border-champagne-200/80 self-start md:self-auto">
                    <button
                        type="button"
                        @click="tab = 'latest'"
                        :class="tab === 'latest' ? 'bg-champagne text-white shadow-sm' : 'text-charcoal-700 hover:text-champagne-700'"
                        class="px-5 py-2 text-xs font-semibold rounded-full transition-all duration-200"
                    >
                        Terbaru
                    </button>
                    <button
                        type="button"
                        @click="tab = 'featured'"
                        :class="tab === 'featured' ? 'bg-champagne text-white shadow-sm' : 'text-charcoal-700 hover:text-champagne-700'"
                        class="px-5 py-2 text-xs font-semibold rounded-full transition-all duration-200"
                    >
                        Unggulan
                    </button>
                </div>
            </div>

            <!-- Tab 1: Produk Terbaru -->
            <div x-show="tab === 'latest'" x-transition.opacity.duration.300ms class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($latestProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Tab 2: Produk Unggulan -->
            <div x-show="tab === 'featured'" x-transition.opacity.duration.300ms style="display: none;" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-14 text-center">
                <x-button href="{{ route('catalog.index') }}" variant="secondary" size="lg">
                    Buka Seluruh Katalog &rarr;
                </x-button>
            </div>
        </div>
    </section>
</x-app-layout>
