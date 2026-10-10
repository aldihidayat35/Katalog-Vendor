<x-app-layout>
    <x-slot:title>{{ $product->name }} - {{ setting('site_name', 'Galeria Invitation') }}</x-slot:title>

    <!-- SEO & Open Graph Meta Tags -->
    @push('seo')
        <meta name="description" content="{{ Str::limit(strip_tags($product->short_description ?? $product->description), 160) }}">
        <meta property="og:title" content="{{ $product->name }} - {{ setting('site_name', 'Galeria Invitation') }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($product->short_description ?? $product->description), 160) }}">
        <meta property="og:image" content="{{ $product->primary_image_url }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="product">
    @endpush

    <!-- Breadcrumb -->
    <div class="bg-ivory-200 border-b border-champagne-200/60 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center text-xs font-medium text-stone-500 overflow-x-auto whitespace-nowrap" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-champagne-700 transition-colors">Beranda</a>
                <span class="mx-2 text-stone-400">/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-champagne-700 transition-colors">Katalog</a>
                <span class="mx-2 text-stone-400">/</span>
                <a href="{{ route('catalog.index', ['kategori' => $product->category->slug]) }}" class="hover:text-champagne-700 transition-colors">
                    {{ $product->category->name }}
                </a>
                <span class="mx-2 text-stone-400">/</span>
                <span class="text-charcoal-900 font-semibold truncate">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Detail Container with Alpine State -->
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16"
        x-data="{
            qty: {{ max(1, (int)$product->min_order) }},
            minQty: {{ max(1, (int)$product->min_order) }},
            unitPrice: {{ $product->final_price }},
            dpPrice: {{ $product->dp_price }},
            paymentOption: 'dp',
            lightboxOpen: false,
            lightboxImage: '',
            activeTab: 'package',

            increment() {
                this.qty += (this.minQty > 1 ? 50 : 1);
            },
            decrement() {
                if (this.qty > this.minQty) {
                    this.qty -= (this.minQty > 1 ? 50 : 1);
                    if (this.qty < this.minQty) this.qty = this.minQty;
                }
            },
            formatRupiah(num) {
                return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
            },
            openLightbox(src) {
                this.lightboxImage = src;
                this.lightboxOpen = true;
            }
        }"
    >
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
            
            <!-- LEFT COLUMN: GALERI FOTO (SWIPER MAIN + THUMBS + LIGHTBOX) -->
            <div class="lg:col-span-6 space-y-4">
                
                <!-- Main Slider Container -->
                <div class="relative bg-white rounded-3xl overflow-hidden border border-stone-200/80 shadow-subtle">
                    <div class="swiper product-main-swiper aspect-[4/3] sm:aspect-square">
                        <div class="swiper-wrapper">
                            @forelse($product->images as $img)
                                <div class="swiper-slide cursor-zoom-in" @click="openLightbox('{{ $img->url }}')">
                                    <img
                                        src="{{ $img->url }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover select-none"
                                    />
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <img
                                        src="{{ asset('images/placeholder-product.svg') }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            @endforelse
                        </div>

                        <!-- Navigation Arrows -->
                        <div class="swiper-button-prev !w-10 !h-10 !rounded-full !bg-white/80 !text-charcoal-800 shadow-md after:!text-sm backdrop-blur-sm"></div>
                        <div class="swiper-button-next !w-10 !h-10 !rounded-full !bg-white/80 !text-charcoal-800 shadow-md after:!text-sm backdrop-blur-sm"></div>
                    </div>

                    <!-- Hint click for lightbox -->
                    <div class="absolute bottom-3 right-3 z-10 pointer-events-none bg-charcoal/70 text-white text-[10px] px-2.5 py-1 rounded-full backdrop-blur-sm flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        Klik gambar untuk perbesar
                    </div>
                </div>

                <!-- Thumbnails Slider -->
                @if($product->images->count() > 1)
                    <div class="swiper product-thumbs-swiper">
                        <div class="swiper-wrapper">
                            @foreach($product->images as $img)
                                <div class="swiper-slide !w-20 !h-20 sm:!w-24 sm:!h-24 rounded-2xl overflow-hidden border-2 border-transparent cursor-pointer transition-all duration-200">
                                    <img
                                        src="{{ $img->url }}"
                                        alt="Thumbnail {{ $loop->iteration }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: INFORMASI, HARGA, STEPPER, & ORDER ACTIONS -->
            <div class="lg:col-span-6 flex flex-col justify-between">
                <div>
                    <!-- Badges & Status Row -->
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <x-badge color="gold">{{ $product->category->name }}</x-badge>
                        
                        @if($product->badge)
                            <x-badge color="champagne">{{ $product->badge }}</x-badge>
                        @endif

                        @if($product->type === 'digital')
                            <x-badge color="blue">Digital Web</x-badge>
                        @else
                            <x-badge color="sage">Cetak Fisik</x-badge>
                        @endif

                        @if($product->stock_status === 'ready')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Slot Produksi Tersedia
                            </span>
                        @elseif($product->stock_status === 'preorder')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                Pre-Order
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                Slot Penuh / Habis
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 leading-tight mb-3">
                        {{ $product->name }}
                    </h1>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <p class="text-sm text-stone-600 leading-relaxed mb-6">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- BLOK HARGA (HARGA ASLI, FINAL, DP, FULL PAYMENT) -->
                    <div class="bg-ivory-100 rounded-3xl p-6 border border-champagne-200 mb-8 space-y-4">
                        <div class="flex items-baseline justify-between gap-4">
                            <div>
                                <span class="text-xs text-stone-500 uppercase tracking-wider font-semibold block mb-0.5">
                                    Harga Satuan
                                </span>
                                <div class="flex items-baseline gap-3">
                                    <span class="text-2xl sm:text-3xl font-serif font-bold text-champagne-700">
                                        {{ rupiah($product->final_price) }}
                                    </span>
                                    @if($product->has_discount)
                                        <span class="text-sm sm:text-base text-stone-400 line-through">
                                            {{ rupiah($product->price) }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-bold bg-rose-600 text-white rounded-full">
                                            Hemat {{ $product->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Skema Opsi Pembayaran DP vs Full Payment -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-champagne-200/80">
                            <!-- Opsi DP Card -->
                            <div
                                @click="paymentOption = 'dp'"
                                :class="paymentOption === 'dp' ? 'border-champagne bg-white ring-2 ring-champagne-400' : 'border-stone-200 bg-stone-50/70 hover:bg-white'"
                                class="p-3.5 rounded-2xl border cursor-pointer transition-all duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-charcoal-900">Uang Muka (DP)</span>
                                    <span class="text-xs font-semibold text-champagne-700">{{ rupiah($product->dp_price) }}</span>
                                </div>
                                <p class="text-[11px] text-stone-500 mt-1 leading-snug">
                                    Cukup bayar DP untuk mengunci slot antrian dan memulai pembuatan draft desain.
                                </p>
                            </div>

                            <!-- Opsi Full Payment Card -->
                            <div
                                @click="paymentOption = 'full'"
                                :class="paymentOption === 'full' ? 'border-champagne bg-white ring-2 ring-champagne-400' : 'border-stone-200 bg-stone-50/70 hover:bg-white'"
                                class="p-3.5 rounded-2xl border cursor-pointer transition-all duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-charcoal-900">Full Payment</span>
                                    <span class="text-xs font-semibold text-champagne-700">
                                        <span x-text="formatRupiah(unitPrice * qty)"></span>
                                    </span>
                                </div>
                                <p class="text-[11px] text-stone-500 mt-1 leading-snug">
                                    Pelunasan langsung di awal pesanan untuk proses cetak tanpa jeda konfirmasi tambahan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FORM PEMESANAN & QUANTITY STEPPER -->
                    <form action="{{ url('/keranjang') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="payment_option" :value="paymentOption">

                        <!-- Quantity Stepper Block -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="quantity-input" class="text-xs font-bold text-charcoal-700 uppercase tracking-wider">
                                    Jumlah Pemesanan (Quantity)
                                </label>
                                <span class="text-xs text-stone-500 font-medium">
                                    Minimal: <strong>{{ $product->min_order }} pcs</strong>
                                </span>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="inline-flex items-center rounded-2xl border border-stone-300 bg-white shadow-sm p-1">
                                    <button
                                        type="button"
                                        @click="decrement()"
                                        :disabled="qty <= minQty || {{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                                        class="w-10 h-10 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-stone-100 disabled:opacity-30 disabled:pointer-events-none transition-colors"
                                        title="Kurangi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>

                                    <input
                                        type="number"
                                        id="quantity-input"
                                        name="quantity"
                                        x-model.number="qty"
                                        @input="if(qty < minQty) qty = minQty;"
                                        min="{{ $product->min_order }}"
                                        class="w-20 text-center font-bold text-charcoal-900 border-none focus:ring-0 text-base"
                                        {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}
                                    />

                                    <button
                                        type="button"
                                        @click="increment()"
                                        :disabled="{{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                                        class="w-10 h-10 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-stone-100 disabled:opacity-30 disabled:pointer-events-none transition-colors"
                                        title="Tambah"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>

                                <!-- Subtotal Estimation -->
                                <div class="text-right flex-1 hidden sm:block">
                                    <span class="text-[11px] text-stone-400 block">Estimasi Total (<span x-text="qty"></span> pcs):</span>
                                    <span class="font-serif text-lg font-bold text-charcoal-900" x-text="formatRupiah(unitPrice * qty)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Row -->
                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                            <!-- Tambah ke Keranjang Button -->
                            <button
                                type="submit"
                                :disabled="{{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                                class="w-full sm:flex-1 py-3.5 px-6 rounded-full font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 shadow-sm {{ $product->stock_status === 'out_of_stock' ? 'bg-stone-300 text-stone-500 cursor-not-allowed' : 'bg-champagne hover:bg-champagne-600 active:bg-champagne-700 text-white shadow-goldglow' }}"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>{{ $product->stock_status === 'out_of_stock' ? 'Stok Tidak Tersedia' : 'Tambahkan ke Keranjang' }}</span>
                            </button>

                            <!-- Chat Admin Button (Direct WA with Product Name & URL) -->
                            @php
                                $waProductMsg = "Halo Admin Galeria, saya tertarik dengan produk {$product->name}: " . url()->current();
                            @endphp
                            <a
                                href="{{ wa_link($waProductMsg) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full sm:w-auto py-3.5 px-6 rounded-full font-semibold text-sm bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-2 transition-colors shadow-sm"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                                <span>Chat Admin</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ACCORDION / TABS: ISI PAKET & SPESIFIKASI BAHAN -->
        <div class="mt-16 pt-12 border-t border-champagne-200">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-center gap-4 border-b border-stone-200 mb-8">
                    <button
                        type="button"
                        @click="activeTab = 'package'"
                        :class="activeTab === 'package' ? 'border-champagne text-champagne-700 font-bold' : 'border-transparent text-stone-500 hover:text-charcoal-800'"
                        class="pb-3 text-base sm:text-lg font-serif border-b-2 transition-all duration-200"
                    >
                        Isi Paket Undangan
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'specs'"
                        :class="activeTab === 'specs' ? 'border-champagne text-champagne-700 font-bold' : 'border-transparent text-stone-500 hover:text-charcoal-800'"
                        class="pb-3 text-base sm:text-lg font-serif border-b-2 transition-all duration-200"
                    >
                        Spesifikasi Bahan &amp; Cetak
                    </button>
                </div>

                <!-- Tab 1: Isi Paket -->
                <div x-show="activeTab === 'package'" x-transition.opacity.duration.200ms class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200 shadow-subtle">
                    <div class="prose prose-stone max-w-none text-charcoal-800 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

                <!-- Tab 2: Spesifikasi -->
                <div x-show="activeTab === 'specs'" x-transition.opacity.duration.200ms style="display: none;" class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200 shadow-subtle">
                    <div class="prose prose-stone max-w-none text-charcoal-800 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ $product->specifications }}
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUK TERKAIT (RELATED PRODUCTS - MAKS 4) -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20 pt-16 border-t border-champagne-200">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <span class="text-xs uppercase font-bold tracking-widest text-champagne-600 block mb-1">
                            KOLEKSI SERUPA
                        </span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-charcoal-900">
                            Produk Terkait Kategori {{ $product->category->name }}
                        </h2>
                    </div>
                    <a href="{{ route('catalog.index', ['kategori' => $product->category->slug]) }}" class="text-xs font-bold text-champagne-700 hover:text-champagne-900 uppercase tracking-wider hidden sm:inline-flex items-center gap-1">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- LIGHTBOX MODAL (ALPINE) -->
        <div
            x-show="lightboxOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 bg-charcoal/90 backdrop-blur-md flex items-center justify-center p-4"
            style="display: none;"
            @keydown.escape.window="lightboxOpen = false"
        >
            <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center" @click.outside="lightboxOpen = false">
                <!-- Close Button -->
                <button
                    type="button"
                    @click="lightboxOpen = false"
                    class="absolute -top-12 right-0 text-white hover:text-champagne-400 transition-colors p-2 text-sm font-semibold flex items-center gap-1"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Tutup (Esc)
                </button>

                <img
                    :src="lightboxImage"
                    alt="{{ $product->name }}"
                    class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl border border-stone-700"
                />
            </div>
        </div>

        <!-- STICKY BOTTOM BAR KHUSUS MOBILE (VIEWPORT < 640px) -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-stone-200 px-4 py-3 shadow-elegant flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] text-stone-400 block leading-tight">Mulai dari</span>
                <span class="font-serif font-bold text-base text-champagne-700">
                    {{ rupiah($product->final_price) }}
                </span>
                @if($product->dp_price > 0)
                    <span class="text-[10px] text-stone-500 block leading-tight">DP {{ rupiah($product->dp_price) }}</span>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ wa_link('Halo Admin, saya tertarik dengan ' . $product->name . ': ' . url()->current()) }}"
                    target="_blank"
                    rel="noopener"
                    class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-sm"
                    title="Chat WhatsApp"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                    </svg>
                </a>

                <form action="{{ url('/keranjang') }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" :value="qty">
                    <input type="hidden" name="payment_option" :value="paymentOption">
                    <button
                        type="submit"
                        :disabled="{{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                        class="px-5 py-2.5 rounded-full text-xs font-bold {{ $product->stock_status === 'out_of_stock' ? 'bg-stone-300 text-stone-500' : 'bg-champagne text-white shadow-goldglow' }}"
                    >
                        {{ $product->stock_status === 'out_of_stock' ? 'Habis' : '+ Keranjang' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Swiper Gallery Initialization Script -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Swiper) {
                    const thumbsSwiper = new window.Swiper('.product-thumbs-swiper', {
                        spaceBetween: 12,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                    });

                    new window.Swiper('.product-main-swiper', {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        thumbs: {
                            swiper: thumbsSwiper,
                        },
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
