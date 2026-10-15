@extends('layouts.app')

@section('title', 'Formulir Pemesanan Undangan (Checkout) - ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8 border-b border-champagne-200/60 pb-5">
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-champagne-700">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('cart.index') }}" class="hover:text-champagne-700">Keranjang</a>
            <span>&rsaquo;</span>
            <span class="text-charcoal-900 font-semibold">Checkout</span>
        </nav>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900">
            Formulir Pemesanan Undangan
        </h1>
        <p class="text-xs sm:text-sm text-stone-500 mt-1">
            Lengkapi data pemesan dan detail acara untuk menyusun draf pesanan resmi Anda.
        </p>
    </div>

    <!-- Error Alert Box -->
    @if ($errors->any())
        <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="flex items-center gap-2 font-bold mb-1">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Mohon periksa kembali formulir Anda:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('checkout.store') }}"
        method="POST"
        x-data="{
            isSubmitting: false,
            subtotal: {{ $summary['subtotal'] }},
            discount: {{ $summary['discount'] }},
            get total() {
                return Math.max(0, this.subtotal - this.discount);
            },
            formatRupiah(num) {
                return 'Rp ' + (new Intl.NumberFormat('id-ID').format(num));
            }
        }"
        @submit="isSubmitting = true"
    >
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- LEFT COLUMN: FORM DATA PEMESAN & PENGIRIMAN -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Section 1: Data Pemesan -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle space-y-5">
                    <div class="border-b border-stone-100 pb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-champagne-700">Langkah 01</span>
                        <h2 class="font-serif text-xl font-bold text-charcoal-900">
                            Data Lengkap Pemesan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="sm:col-span-2">
                            <label for="customer_name" class="block text-xs font-bold text-charcoal-700 mb-1">
                                Nama Lengkap Pemesan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="customer_name"
                                name="customer_name"
                                value="{{ old('customer_name', $user->name) }}"
                                required
                                placeholder="Contoh: Rian Anggara"
                                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                            >
                            @error('customer_name')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="customer_email" class="block text-xs font-bold text-charcoal-700 mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="email"
                                id="customer_email"
                                name="customer_email"
                                value="{{ old('customer_email', $user->email) }}"
                                required
                                placeholder="nama@email.com"
                                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                            >
                            @error('customer_email')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- WhatsApp / HP -->
                        <div>
                            <label for="customer_phone" class="block text-xs font-bold text-charcoal-700 mb-1">
                                Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="customer_phone"
                                name="customer_phone"
                                value="{{ old('customer_phone', $user->phone) }}"
                                required
                                placeholder="081234567890"
                                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                            >
                            <span class="text-[10px] text-stone-400 mt-0.5 block">Format Indonesia aktif (contoh: 0812xxxxxx)</span>
                            @error('customer_phone')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Akun Instagram -->
                        <div>
                            <label for="instagram_account" class="block text-xs font-bold text-charcoal-700 mb-1">
                                Akun Instagram (Opsional)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400 text-sm">@</span>
                                <input
                                    type="text"
                                    id="instagram_account"
                                    name="instagram_account"
                                    value="{{ old('instagram_account', $user->instagram ? ltrim($user->instagram, '@') : '') }}"
                                    placeholder="username_mempelai"
                                    class="w-full pl-8 rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                                >
                            </div>
                            @error('instagram_account')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Acara -->
                        <div>
                            <label for="event_date" class="block text-xs font-bold text-charcoal-700 mb-1">
                                Tanggal Acara / Pernikahan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="event_date"
                                name="event_date"
                                value="{{ old('event_date') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                            >
                            @error('event_date')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Pengiriman & Catatan -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle space-y-5">
                    <div class="border-b border-stone-100 pb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-champagne-700">Langkah 02</span>
                        <h2 class="font-serif text-xl font-bold text-charcoal-900">
                            Pengiriman &amp; Catatan Khusus
                        </h2>
                    </div>

                    <!-- Banner Info Free Ongkir Provinsi Riau -->
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50/70 to-emerald-50 border border-emerald-200/90 p-4 sm:p-5 shadow-sm">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white tracking-wide uppercase shadow-xs">
                                        🎉 Free Ongkir Se-Riau
                                    </span>
                                    <span class="text-xs font-bold text-emerald-950">
                                        Gratis Ongkos Kirim Seluruh Provinsi Riau
                                    </span>
                                </div>
                                <p class="text-xs text-emerald-900 leading-relaxed font-normal">
                                    Kabar gembira! Khusus pengiriman ke seluruh wilayah <strong class="font-semibold text-emerald-950">Provinsi Riau</strong> mendapatkan fasilitas <strong class="font-semibold text-emerald-950">Free Ongkir (Rp 0)</strong> tanpa minimum belanja.
                                </p>
                                <div class="mt-2.5 pt-2 border-t border-emerald-200/70 flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-emerald-800">
                                    <span class="font-semibold">Cakupan Wilayah:</span>
                                    <span>Pekanbaru • Dumai • Kampar • Siak • Pelalawan • Bengkalis • Indragiri Hulu • Indragiri Hilir • Rokan Hulu • Rokan Hilir • Kuantan Singingi • Kepulauan Meranti</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pengiriman -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="shipping_method" class="block text-xs font-bold text-charcoal-700">
                                Metode Pengiriman <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                Bebas Ongkir (Rp 0)
                            </span>
                        </div>
                        <select
                            id="shipping_method"
                            name="shipping_method"
                            required
                            class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                        >
                            <option value="">-- Pilih Metode Pengiriman --</option>
                            @foreach($shippingMethods as $sm)
                                <option
                                    value="{{ $sm['name'] }}"
                                    {{ old('shipping_method') === $sm['name'] ? 'selected' : '' }}
                                >
                                    {{ $sm['name'] }} @if(!empty($sm['desc'])) ({{ $sm['desc'] }}) @endif — Free Ongkir se-Provinsi Riau
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-emerald-700 mt-1.5 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Semua pilihan ekspedisi di atas <strong>bebas biaya kirim (Free Ongkir Rp 0)</strong> untuk pengiriman di area Provinsi Riau.</span>
                        </p>
                        @error('shipping_method')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-charcoal-700 mb-1">
                            Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            id="shipping_address"
                            name="shipping_address"
                            rows="3"
                            required
                            placeholder="Alamat jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten di Provinsi Riau, kode pos"
                            class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne leading-relaxed"
                        >{{ old('shipping_address', $user->address) }}</textarea>
                        <p class="text-[11px] text-stone-500 mt-1 flex items-center gap-1">
                            <span>💡 Cantumkan nama kecamatan &amp; kabupaten/kota di Provinsi Riau untuk memastikan pengiriman Free Ongkir tepat sasaran.</span>
                        </p>
                        @error('shipping_address')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-charcoal-700 mb-1">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="2"
                            placeholder="Contoh: Nama kedua mempelai (Rian & Maya), warna amplop request sage green, dll."
                            class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne leading-relaxed"
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Section 3: Informasi Pembayaran & DP -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle space-y-3">
                    <div class="border-b border-stone-100 pb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-champagne-700">Langkah 03</span>
                        <h2 class="font-serif text-xl font-bold text-charcoal-900">
                            Informasi Pembayaran &amp; DP
                        </h2>
                    </div>

                    <div class="p-4 rounded-2xl bg-champagne-50/70 border border-champagne-200/90 flex items-start gap-3">
                        <span class="text-xl shrink-0 mt-0.5">💬</span>
                        <div class="text-xs text-stone-600 leading-relaxed space-y-1">
                            <span class="font-bold text-charcoal-900 block">Negosiasi DP &amp; Termin Langsung via WhatsApp</span>
                            <p>
                                Setelah formulir ini dikirim, sistem akan mengarahkan Anda ke WhatsApp Admin dengan rincian pesanan lengkap. Jika Anda ingin melakukan pembayaran dengan skema Uang Muka (DP) atau termin pelunasan, silakan diskusikan langsung dengan Admin.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: STICKY ORDER SUMMARY -->
            <div class="lg:col-span-5">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle space-y-5">
                        <div class="border-b border-stone-100 pb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-champagne-700">Ringkasan</span>
                            <h2 class="font-serif text-xl font-bold text-charcoal-900">
                                Pesanan Anda
                            </h2>
                        </div>

                        <!-- Product List Snapshot in Checkout -->
                        <div class="divide-y divide-stone-100 max-h-72 overflow-y-auto pr-1">
                            @foreach($summary['items'] as $item)
                                <div class="py-3 flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-ivory-100 overflow-hidden flex-shrink-0 border border-stone-200">
                                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-charcoal-900 truncate">
                                            {{ $item->product->name }}
                                        </h4>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[11px] text-stone-500">
                                                {{ $item->quantity }} pcs × {{ rupiah($item->unit_price ?? $item->product->final_price) }}
                                            </span>
                                            @if(!empty($item->is_wholesale))
                                                <span class="px-1.5 py-0.2 text-[9px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                                    Grosir
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-serif font-bold text-charcoal-900 block">
                                            {{ rupiah($item->subtotal ?? $item->item_subtotal) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Breakdown -->
                        <div class="pt-4 border-t border-stone-100 space-y-2.5 text-xs text-stone-600">
                            <div class="flex justify-between">
                                <span>Subtotal Pesanan</span>
                                <span class="font-semibold text-charcoal-900">{{ rupiah($summary['subtotal']) }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span>Ongkos Kirim</span>
                                <span class="text-emerald-700 font-semibold inline-flex items-center gap-1.5">
                                    <span class="text-[10px] px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200 font-bold">Free Ongkir Riau</span>
                                    <span>Rp 0</span>
                                </span>
                            </div>

                            <template x-if="discount > 0">
                                <div class="flex justify-between text-emerald-600 font-semibold">
                                    <span>Diskon Promo ({{ session('applied_promo_code') }})</span>
                                    <span>-{{ rupiah($summary['discount']) }}</span>
                                </div>
                            </template>

                            <div class="flex justify-between items-baseline pt-3 border-t border-stone-200 text-sm">
                                <div>
                                    <span class="font-serif font-bold text-base text-charcoal-900 block">Total Pesanan:</span>
                                    <span class="text-[10px] text-stone-500 font-medium">DP dapat dinegosiasikan di WA</span>
                                </div>
                                <span class="font-serif font-bold text-2xl text-champagne-700" x-text="formatRupiah(total)">
                                    {{ rupiah($summary['total']) }}
                                </span>
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <div class="pt-3">
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : 'hover:bg-champagne-600 active:scale-95'"
                                class="w-full py-4 px-6 rounded-full bg-champagne text-white font-semibold text-sm shadow-goldglow flex items-center justify-center gap-2 transition-all duration-200"
                            >
                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <span x-text="isSubmitting ? 'Memproses Pesanan...' : 'Kirim Pesanan & Lanjutkan ke WhatsApp'">Kirim Pesanan &amp; Lanjutkan ke WhatsApp</span>
                            </button>
                        </div>

                        <!-- Info note -->
                        <div class="p-3.5 rounded-2xl bg-ivory-100/80 border border-champagne-200 text-[11px] text-stone-600 leading-relaxed flex items-start gap-2">
                            <svg class="w-4 h-4 text-champagne-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span>Setelah menekan tombol di atas, data pesanan akan disimpan di sistem dan Anda otomatis dialihkan ke WhatsApp Admin dengan format pesanan lengkap.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
