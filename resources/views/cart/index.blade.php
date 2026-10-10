@extends('layouts.app')

@section('title', 'Keranjang Belanja - ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header -->
    <div class="mb-8 border-b border-champagne-200/60 pb-5">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900">
            Keranjang Belanja
        </h1>
        <p class="text-xs sm:text-sm text-stone-500 mt-1">
            Periksa pilihan desain undangan, jumlah pesanan, dan skema pembayaran Anda.
        </p>
    </div>

    <!-- Alert Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-sky-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if($summary['items']->isEmpty())
        <!-- EMPTY STATE -->
        <div class="bg-white rounded-3xl p-12 text-center border border-stone-200 shadow-subtle max-w-xl mx-auto my-12">
            <div class="w-20 h-20 bg-champagne-100 text-champagne-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h2 class="font-serif text-2xl font-bold text-charcoal-900 mb-2">Keranjang Anda Masih Kosong</h2>
            <p class="text-stone-500 text-sm mb-8 leading-relaxed">
                Anda belum memilih desain undangan pernikahan. Jelajahi katalog eksklusif kami dan temukan tema impian Anda.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-sm shadow-goldglow transition-all duration-200">
                Jelajahi Katalog Desain &rarr;
            </a>
        </div>
    @else
        <!-- CART CONTENT WITH ALPINE STATE -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12"
             x-data="{
                 paymentOption: '{{ $paymentOption }}',
                 subtotal: {{ $summary['subtotal'] }},
                 dpSubtotal: {{ $summary['dp_subtotal'] }},
                 discount: {{ $summary['discount'] }},
                 get total() {
                     if (this.paymentOption === 'dp') {
                         return this.dpSubtotal;
                     }
                     return Math.max(0, this.subtotal - this.discount);
                 },
                 formatRupiah(num) {
                     return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
                 },
                 selectPayment(opt) {
                     this.paymentOption = opt;
                     window.location.href = '{{ route('cart.index') }}?payment_option=' + opt;
                 }
             }">

            <!-- LEFT: CART ITEMS LIST -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-stone-200 shadow-subtle divide-y divide-stone-100 overflow-hidden">
                    @foreach($summary['items'] as $item)
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 transition-colors hover:bg-ivory-50/50">
                            <!-- Thumbnail -->
                            <a href="{{ route('product.show', $item->product->slug) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-ivory-100 border border-stone-200 flex-shrink-0">
                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            </a>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-champagne-700 block mb-0.5">
                                    {{ $item->product->category->name ?? 'Undangan' }} • {{ $item->product->type === 'printed' ? 'Cetak' : 'Digital' }}
                                </span>
                                <h3 class="font-serif font-bold text-base sm:text-lg text-charcoal-900 truncate">
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-champagne-600 transition-colors">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>

                                <div class="flex items-baseline gap-2 mt-1">
                                    <span class="text-sm font-semibold text-charcoal-900">
                                        {{ rupiah($item->product->final_price) }}
                                    </span>
                                    @if($item->product->has_discount)
                                        <span class="text-xs text-stone-400 line-through">
                                            {{ rupiah($item->product->price) }}
                                        </span>
                                    @endif
                                    <span class="text-[11px] text-stone-500">/ pcs</span>
                                </div>

                                <div class="text-[11px] text-stone-500 mt-0.5">
                                    Uang Muka (DP): <strong>{{ rupiah($item->product->dp_price) }}</strong> / pcs
                                </div>
                            </div>

                            <!-- Stepper & Subtotal -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-3 pt-2 sm:pt-0">
                                <!-- Quantity Stepper Form -->
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center rounded-xl border border-stone-200 bg-white p-0.5 shadow-sm">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ max($item->product->min_order, $item->quantity - 1) }}"
                                        @if($item->quantity <= $item->product->min_order) disabled @endif
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-stone-600 hover:bg-stone-100 disabled:opacity-30 disabled:pointer-events-none"
                                        title="Kurangi"
                                    >
                                        -
                                    </button>

                                    <span class="w-12 text-center text-xs font-bold text-charcoal-900">
                                        {{ $item->quantity }}
                                    </span>

                                    <button
                                        type="submit"
                                        name="quantity"
                                        value="{{ $item->quantity + 1 }}"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-stone-600 hover:bg-stone-100"
                                        title="Tambah"
                                    >
                                        +
                                    </button>
                                </form>

                                <!-- Subtotal per item -->
                                <div class="text-right">
                                    <span class="text-[10px] text-stone-400 block sm:inline">Subtotal:</span>
                                    <span class="font-serif font-bold text-sm sm:text-base text-champagne-700 block">
                                        {{ rupiah($item->subtotal) }}
                                    </span>
                                </div>

                                <!-- Delete button -->
                                <form action="{{ route('cart.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-medium inline-flex items-center gap-1 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Back to catalog link -->
                <div class="pt-2">
                    <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-900 inline-flex items-center gap-1 transition-colors">
                        &larr; Lanjutkan Memilih Desain Lain
                    </a>
                </div>
            </div>

            <!-- RIGHT: SUMMARY & CHECKOUT SIDEBAR -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Payment Option Selector Card -->
                <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-subtle space-y-4">
                    <h2 class="font-serif text-lg font-bold text-charcoal-900 flex items-center gap-2">
                        <span>Pilihan Skema Pembayaran</span>
                    </h2>

                    <div class="space-y-3">
                        <!-- Full Payment Radio -->
                        <label
                            @click="selectPayment('full')"
                            :class="paymentOption === 'full' ? 'border-champagne bg-champagne-50/40 ring-2 ring-champagne-400' : 'border-stone-200 bg-white'"
                            class="flex items-start gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all duration-200"
                        >
                            <input type="radio" name="payment_choice" value="full" :checked="paymentOption === 'full'" class="mt-1 text-champagne focus:ring-champagne">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-charcoal-900">Full Payment (Lunas)</span>
                                </div>
                                <p class="text-[11px] text-stone-500 mt-0.5 leading-snug">
                                    Pelunasan penuh di awal. Kupon diskon dan potongan promo dapat diterapkan.
                                </p>
                            </div>
                        </label>

                        <!-- DP Radio -->
                        <label
                            @click="selectPayment('dp')"
                            :class="paymentOption === 'dp' ? 'border-champagne bg-champagne-50/40 ring-2 ring-champagne-400' : 'border-stone-200 bg-white'"
                            class="flex items-start gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all duration-200"
                        >
                            <input type="radio" name="payment_choice" value="dp" :checked="paymentOption === 'dp'" class="mt-1 text-champagne focus:ring-champagne">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-charcoal-900">Uang Muka (DP {{ setting('dp_percentage', 50) }}%)</span>
                                </div>
                                <p class="text-[11px] text-stone-500 mt-0.5 leading-snug">
                                    Kunci antrian produksi dengan bayar DP separuh. Sisa dilunasi saat undangan siap kirim.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Promo Code Form Card -->
                <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-subtle space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-charcoal-700">
                        Kode Promo / Kupon
                    </h3>

                    @if(session('applied_promo_code'))
                        <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-emerald-800 block">
                                    {{ session('applied_promo_code') }}
                                </span>
                                <span class="text-[11px] text-emerald-600">
                                    Hemat {{ rupiah($summary['discount']) }} (Khusus Full Payment)
                                </span>
                            </div>
                            <form action="{{ route('cart.promo.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.promo.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input
                                type="text"
                                name="code"
                                placeholder="Masukkan kode promo..."
                                required
                                class="flex-1 rounded-xl text-xs border-stone-300 focus:border-champagne focus:ring-champagne uppercase"
                            >
                            <button type="submit" class="px-4 py-2 bg-charcoal-900 hover:bg-charcoal text-white rounded-xl text-xs font-bold transition-colors">
                                Terapkan
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Price Summary Card -->
                <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-subtle space-y-4">
                    <h3 class="font-serif text-lg font-bold text-charcoal-900">
                        Ringkasan Belanja
                    </h3>

                    <div class="space-y-2.5 text-xs text-stone-600">
                        <div class="flex justify-between">
                            <span>Total Jumlah</span>
                            <span class="font-medium text-charcoal-900">{{ $summary['total_quantity'] }} pcs</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Subtotal Pesanan</span>
                            <span class="font-medium text-charcoal-900">{{ rupiah($summary['subtotal']) }}</span>
                        </div>

                        <!-- Diskon Promo (Tampil hanya jika ada) -->
                        <template x-if="discount > 0 && paymentOption === 'full'">
                            <div class="flex justify-between text-emerald-600 font-semibold">
                                <span>Diskon Promo ({{ session('applied_promo_code') }})</span>
                                <span>-{{ rupiah($summary['discount']) }}</span>
                            </div>
                        </template>

                        <div class="flex justify-between pt-2 border-t border-stone-100">
                            <span class="font-bold text-charcoal-900">Skema Terpilih</span>
                            <span class="font-bold text-champagne-700" x-text="paymentOption === 'dp' ? 'Uang Muka (DP)' : 'Full Payment'"></span>
                        </div>

                        <div class="flex justify-between items-baseline pt-2 border-t border-stone-200 text-sm">
                            <span class="font-serif font-bold text-base text-charcoal-900">
                                Total Bayar:
                            </span>
                            <span class="font-serif font-bold text-xl text-champagne-700" x-text="formatRupiah(total)">
                                {{ rupiah($summary['total']) }}
                            </span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <div class="pt-2">
                        <a href="{{ route('checkout.index') }}" class="w-full py-4 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-sm shadow-goldglow flex items-center justify-center gap-2 transition-all duration-200">
                            <span>Lanjutkan ke Checkout</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
