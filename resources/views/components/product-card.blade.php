@props([
    'product',
])

@php
$hasDiscount = $product->has_discount;
$isOutOfStock = $product->stock_status === 'out_of_stock';
$isPreorder = $product->stock_status === 'preorder';
@endphp

<div class="group relative flex flex-col bg-white rounded-3xl overflow-hidden border border-stone-200/80 hover:border-champagne-400 hover:shadow-elegant transition-all duration-300 {{ $isOutOfStock ? 'opacity-75' : '' }}">
    
    <!-- Image & Badges Container -->
    <a href="{{ route('product.show', $product->slug) }}" class="relative block aspect-[4/3] bg-stone-100 overflow-hidden">
        <img
            src="{{ $product->primary_image_url }}"
            alt="{{ $product->name }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
        />

        @if($isOutOfStock)
            <div class="absolute inset-0 bg-charcoal/40 backdrop-blur-[1px] flex items-center justify-center">
                <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider bg-rose-600 text-white rounded-full shadow-md">
                    Stok Habis
                </span>
            </div>
        @endif

        <!-- Floating Top Left Badges -->
        <div class="absolute top-3 left-3 flex flex-col items-start gap-1.5 z-10">
            @if($product->badge)
                <x-badge color="gold">{{ $product->badge }}</x-badge>
            @endif

            @if($product->type === 'digital')
                <x-badge color="blue">Digital Web</x-badge>
            @else
                <x-badge color="sage">Cetak Fisik</x-badge>
            @endif

            @if($isPreorder && !$isOutOfStock)
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-amber-500 text-white rounded-full shadow-sm">
                    Pre-Order
                </span>
            @endif
        </div>

        <!-- Floating Top Right Discount Badge -->
        @if($hasDiscount && !$isOutOfStock)
            <div class="absolute top-3 right-3 z-10">
                <span class="px-2.5 py-1 text-xs font-bold bg-rose-600 text-white rounded-full shadow-sm">
                    -{{ $product->discount_percentage }}%
                </span>
            </div>
        @endif
    </a>

    <!-- Product Info Section -->
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <!-- Category Tag & Color Dot -->
            <div class="flex items-center justify-between mb-1.5">
                <div class="flex items-center gap-1.5 min-w-0">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-champagne-700 truncate">
                        {{ $product->category->name ?? 'Koleksi' }}
                    </span>
                    @if($product->theme_color && $product->theme_color_data)
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-stone-100/90 text-stone-600 text-[10px] font-medium border border-stone-200/60 shrink-0" title="Tema: {{ $product->theme_color_label }}">
                            <span class="w-2 h-2 rounded-full border border-black/10 shrink-0" style="background-color: {{ $product->theme_color_hex }};"></span>
                            <span class="hidden sm:inline">{{ $product->theme_color_label }}</span>
                        </span>
                    @endif
                </div>
                @if($product->min_order > 1)
                    <span class="text-[10px] text-stone-400 font-medium shrink-0">
                        Min. {{ $product->min_order }} pcs
                    </span>
                @endif
            </div>

            <!-- Title as clickable link -->
            <h3 class="font-serif text-base sm:text-lg font-bold text-charcoal-900 group-hover:text-champagne-700 transition-colors line-clamp-1">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Short Description -->
            @if($product->short_description)
                <p class="text-xs text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                    {{ $product->short_description }}
                </p>
            @endif
        </div>

        <!-- Price & Action Section -->
        <div class="mt-4 pt-3 border-t border-stone-100 flex items-end justify-between gap-2">
            <div>
                <span class="text-[10px] text-stone-400 block leading-tight">Harga Satuan</span>
                <x-price :price="$product->price" :discountPrice="$product->discount_price" size="md" />
                @if($product->dp_price > 0)
                    <span class="text-[10px] text-stone-500 block mt-0.5">
                        DP mulai <strong class="text-charcoal-800">{{ rupiah($product->dp_price) }}</strong>
                    </span>
                @endif
            </div>

            <a
                href="{{ route('product.show', $product->slug) }}"
                class="w-9 h-9 flex-shrink-0 rounded-full bg-champagne text-white group-hover:bg-champagne-600 flex items-center justify-center transition-all duration-200 shadow-sm group-hover:scale-105"
                title="Lihat Detail Produk"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
