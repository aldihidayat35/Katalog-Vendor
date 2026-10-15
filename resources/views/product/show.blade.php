@php
    $productCleanDesc = Str::limit(strip_tags($product->short_description ?? $product->description), 160);
    $productKeywords = "undangan {$product->name}, cetak undangan {$product->category->name}, undangan pernikahan mewah, undangan pernikahan online, {$product->slug}";
    $productCanonical = route('product.show', $product->slug);
    $productOgImage = $product->primary_image_url;
    $productBreadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Beranda',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Katalog',
                'item' => route('catalog.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->category->name,
                'item' => route('catalog.index', ['kategori' => $product->category->slug]),
            ],
            [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $product->name,
                'item' => route('product.show', $product->slug),
            ],
        ],
    ];

    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => [$product->primary_image_url],
        'description' => $productCleanDesc,
        'sku' => 'INV-' . $product->id,
        'category' => $product->category->name,
        'brand' => [
            '@type' => 'Brand',
            'name' => setting('site_name', 'Galeria Invitation'),
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => route('product.show', $product->slug),
            'priceCurrency' => 'IDR',
            'price' => (string) $product->final_price,
            'priceValidUntil' => now()->addYear()->format('Y-12-31'),
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => $product->stock_status === 'out_of_stock' ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => setting('site_name', 'Galeria Invitation'),
            ],
        ],
    ];
@endphp

<x-app-layout 
    :title="$product->name . ' - Undangan Pernikahan'"
    :description="$productCleanDesc"
    :keywords="$productKeywords"
    :canonicalUrl="$productCanonical"
    :ogImage="$productOgImage"
    ogType="product"
>
    <!-- Schema.org BreadcrumbList & Product Structured Data -->
    @push('schema')
    <script type="application/ld+json">
    {!! json_encode($productBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
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

    <!-- Product Detail Alpine State Definition -->
    <script>
        function productDetail() {
            return {
                qty: {{ max(1, (int)$product->min_order) }},
                minQty: {{ max(1, (int)$product->min_order) }},
                baseUnitPrice: {{ $product->final_price }},
                tiers: @json($product->priceTiers->sortBy('min_quantity')->values()),
                lightboxOpen: false,
                lightboxImage: '',
                activeTab: 'package',
                isSubmitting: false,
                toastOpen: false,
                toastMessage: '',

                get unitPrice() {
                    if (!this.tiers || this.tiers.length === 0) {
                        return this.baseUnitPrice;
                    }
                    const currentQty = Number(this.qty) || 0;
                    const matched = this.tiers.find(t => {
                        const min = Number(t.min_quantity);
                        const max = t.max_quantity ? Number(t.max_quantity) : null;
                        return currentQty >= min && (max === null || currentQty <= max);
                    });
                    if (matched) {
                        return Number(matched.unit_price);
                    }
                    const sorted = [...this.tiers].sort((a, b) => Number(b.min_quantity) - Number(a.min_quantity));
                    if (sorted.length > 0 && currentQty >= Number(sorted[0].min_quantity)) {
                        return Number(sorted[0].unit_price);
                    }
                    return this.baseUnitPrice;
                },

                get isWholesaleActive() {
                    if (!this.tiers || this.tiers.length === 0) return false;
                    const currentQty = Number(this.qty) || 0;
                    const matched = this.tiers.find(t => {
                        const min = Number(t.min_quantity);
                        const max = t.max_quantity ? Number(t.max_quantity) : null;
                        return currentQty >= min && (max === null || currentQty <= max);
                    });
                    if (matched) {
                        return Number(matched.unit_price) < this.baseUnitPrice;
                    }
                    const sorted = [...this.tiers].sort((a, b) => Number(b.min_quantity) - Number(a.min_quantity));
                    if (sorted.length > 0 && currentQty >= Number(sorted[0].min_quantity)) {
                        return Number(sorted[0].unit_price) < this.baseUnitPrice;
                    }
                    return false;
                },

                isTierActive(tier) {
                    const currentQty = Number(this.qty) || 0;
                    const min = Number(tier.min_quantity);
                    const max = tier.max_quantity ? Number(tier.max_quantity) : null;
                    if (max === null) {
                        return currentQty >= min;
                    }
                    return currentQty >= min && currentQty <= max;
                },

                selectTier(tier) {
                    this.qty = Number(tier.min_quantity);
                },

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
                },
                showToast(msg) {
                    this.toastMessage = msg;
                    this.toastOpen = true;
                    setTimeout(() => { this.toastOpen = false; }, 3500);
                },
                addToCart() {
                    @guest
                        window.location.href = "{{ route('cart.login-prompt', ['redirect' => url()->current()]) }}";
                        return;
                    @endguest

                    this.isSubmitting = true;
                    fetch("{{ route('cart.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            product_id: {{ $product->id }},
                            quantity: this.qty
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.showToast(data.message || 'Produk berhasil ditambahkan ke keranjang!');
                            window.dispatchEvent(new CustomEvent('cart-updated', {
                                detail: { count: data.cart_count }
                            }));
                        } else {
                            alert(data.message || 'Gagal menambahkan produk ke keranjang.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
                }
            };
        }
        window.productDetail = productDetail;
        if (window.Alpine) {
            window.Alpine.data('productDetail', productDetail);
        } else {
            document.addEventListener('alpine:init', () => {
                window.Alpine.data('productDetail', productDetail);
            });
        }
    </script>

    <!-- Product Detail Container with Alpine State -->
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16"
        x-data="productDetail()"
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

                        @if($product->theme_color && $product->theme_color_data)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-charcoal-800 border border-stone-200/80 shadow-2xs" title="Tema Warna: {{ $product->theme_color_label }}">
                                <span class="w-2.5 h-2.5 rounded-full border border-black/15 shrink-0" style="background-color: {{ $product->theme_color_hex }};"></span>
                                <span>Tema {{ $product->theme_color_label }}</span>
                            </span>
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
                                <div class="flex items-baseline gap-3 flex-wrap">
                                    <span class="text-2xl sm:text-3xl font-serif font-bold text-champagne-700" x-text="formatRupiah(unitPrice)">
                                        {{ rupiah($product->final_price) }}
                                    </span>
                                    <span class="text-xs text-stone-500">/ pcs</span>

                                    <template x-if="isWholesaleActive">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-bold bg-emerald-600 text-white rounded-full shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            Harga Grosir Aktif
                                        </span>
                                    </template>

                                    @if($product->has_discount)
                                        <template x-if="!isWholesaleActive">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm sm:text-base text-stone-400 line-through">
                                                    {{ rupiah($product->price) }}
                                                </span>
                                                <span class="px-2 py-0.5 text-xs font-bold bg-rose-600 text-white rounded-full">
                                                    Hemat {{ $product->discount_percentage }}%
                                                </span>
                                            </div>
                                        </template>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- TABEL / KARTU HARGA GROSIR BERTAHAP -->
                        @if($product->hasPriceTiers())
                            <div class="pt-3 border-t border-champagne-200/80">
                                <div class="flex items-center justify-between mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-champagne-600"></span>
                                        <span class="text-xs font-bold text-charcoal-900 uppercase tracking-wider">
                                            Daftar Harga Grosir
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-stone-500">
                                        Klik baris untuk pilih tier
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-{{ min(3, max(2, $product->priceTiers->count())) }} gap-2.5">
                                    <template x-for="tier in tiers" :key="tier.id">
                                        <div
                                            @click="selectTier(tier)"
                                            :class="isTierActive(tier) ? 'bg-champagne-50/90 border-champagne-500 ring-2 ring-champagne-400/60 shadow-sm' : 'bg-white/90 hover:bg-white border-stone-200 hover:border-champagne-300'"
                                            class="p-3 rounded-2xl border cursor-pointer transition-all duration-200 relative group"
                                        >
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-charcoal-900" x-text="tier.range_label || (tier.max_quantity ? tier.min_quantity + ' - ' + tier.max_quantity + ' pcs' : '≥ ' + tier.min_quantity + ' pcs')"></span>
                                                <span
                                                    x-show="isTierActive(tier)"
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-600 text-white uppercase tracking-wider shadow-2xs"
                                                >
                                                    Pilihan Anda
                                                </span>
                                            </div>
                                            <div class="flex items-baseline gap-1">
                                                <span class="font-serif text-sm font-bold text-champagne-700" x-text="formatRupiah(tier.unit_price)"></span>
                                                <span class="text-[10px] text-stone-500">/ pcs</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endif

                        <!-- Info Opsi Negosiasi DP via WhatsApp -->
                        <div class="p-3.5 rounded-2xl bg-champagne-50/70 border border-champagne-200/80 flex items-start gap-2.5">
                            <span class="text-base shrink-0 mt-0.5">💬</span>
                            <div class="text-xs text-stone-600 leading-relaxed">
                                <strong class="text-charcoal-900 block font-semibold mb-0.5">Fleksibilitas Pembayaran DP &amp; Termin</strong>
                                Ingin pembayaran bertahap (DP)? Skema DP dan termin pelunasan dapat Anda bicarakan langsung dengan admin kami via WhatsApp setelah checkout.
                            </div>
                        </div>
                    </div>

                    <!-- FORM PEMESANAN & QUANTITY STEPPER -->
                    <form action="{{ url('/keranjang') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

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
                            <!-- Tambah ke Keranjang Button with AJAX/Alpine -->
                            <button
                                type="button"
                                @click="addToCart()"
                                :disabled="isSubmitting || {{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                                class="w-full sm:flex-1 py-3.5 px-6 rounded-full font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 shadow-sm {{ $product->stock_status === 'out_of_stock' ? 'bg-stone-300 text-stone-500 cursor-not-allowed' : 'bg-champagne hover:bg-champagne-600 active:bg-champagne-700 text-white shadow-goldglow' }}"
                            >
                                <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <svg x-show="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" style="display: none;">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isSubmitting ? 'Menambahkan...' : '{{ $product->stock_status === 'out_of_stock' ? 'Stok Tidak Tersedia' : 'Tambahkan ke Keranjang' }}'"></span>
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

                <button
                    type="button"
                    @click="addToCart()"
                    :disabled="isSubmitting || {{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}"
                    class="px-5 py-2.5 rounded-full text-xs font-bold transition-all {{ $product->stock_status === 'out_of_stock' ? 'bg-stone-300 text-stone-500' : 'bg-champagne text-white shadow-goldglow' }}"
                >
                    <span x-text="isSubmitting ? '...' : '{{ $product->stock_status === 'out_of_stock' ? 'Habis' : '+ Keranjang' }}'"></span>
                </button>
            </div>
        </div>

        <!-- Floating Success Toast Notification (Alpine) -->
        <div
            x-show="toastOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="fixed bottom-6 right-6 z-50 bg-charcoal-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-champagne-400/40"
            style="display: none;"
        >
            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="text-xs">
                <p class="font-bold text-white" x-text="toastMessage"></p>
                <a href="{{ route('cart.index') }}" class="text-champagne-300 hover:text-champagne-200 font-semibold underline mt-0.5 inline-block">
                    Buka Keranjang Belanja &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts for Product Detail & Swiper -->
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
