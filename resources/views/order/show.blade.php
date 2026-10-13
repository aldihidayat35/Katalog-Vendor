@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-8">
    <!-- Breadcrumb & Header -->
    <div class="border-b border-champagne-200/60 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-champagne-700">Beranda</a>
                <span>&rsaquo;</span>
                <a href="{{ route('order.index') }}" class="hover:text-champagne-700">Pesanan Saya</a>
                <span>&rsaquo;</span>
                <span class="text-charcoal-900 font-semibold">{{ $order->order_number }}</span>
            </nav>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-charcoal-900">
                Detail Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-xs text-stone-500 mt-1">
                Dibuat pada {{ $order->created_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <!-- WA Follow-up Button -->
        <a
            href="{{ $waLink }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition-colors self-start sm:self-auto"
        >
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Hubungi Admin via WhatsApp</span>
        </a>
    </div>

    <!-- Status Alert Banner -->
    <div class="rounded-2xl p-4 sm:p-5 border text-xs sm:text-sm flex items-start gap-3 {{ match($order->status) {
        'confirmed' => 'bg-emerald-50 border-emerald-200 text-emerald-900',
        'completed' => 'bg-purple-50 border-purple-200 text-purple-900',
        'cancelled' => 'bg-rose-50 border-rose-200 text-rose-900',
        'contacted' => 'bg-blue-50 border-blue-200 text-blue-900',
        default => 'bg-amber-50 border-amber-200 text-amber-900',
    } }}">
        <div class="mt-0.5 flex-shrink-0">
            @if($order->status === 'confirmed' || $order->status === 'completed')
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($order->status === 'cancelled')
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @else
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @endif
        </div>
        <div class="leading-relaxed">
            @if($order->status === 'lead')
                <strong class="font-bold block mb-0.5">Status: Menunggu Koordinasi Admin</strong>
                Pesanan Anda telah tercatat di sistem kami. Silakan hubungi admin via WhatsApp untuk konfirmasi {{ $order->is_dp ? 'pembayaran uang muka (DP 50%) sebesar ' . rupiah($order->dp_amount) : 'pembayaran lunas' }} dan konsultasi pengisian teks undangan.
            @elseif($order->status === 'contacted')
                <strong class="font-bold block mb-0.5">Status: Sedang Dalam Koordinasi</strong>
                Admin kami telah menghubungi Anda atau sedang menyiapkan sampel/draf data undangan Anda.
            @elseif($order->status === 'confirmed')
                <strong class="font-bold block mb-0.5">Status: {{ $order->is_dp ? 'DP Diterima & Masuk Antrian Produksi' : 'Pembayaran Terverifikasi & Dalam Produksi' }}</strong>
                @if($order->is_dp)
                    Pembayaran DP 50% sebesar <strong>{{ rupiah($order->dp_amount) }}</strong> telah dikonfirmasi! Pesanan Anda saat ini sedang dikerjakan. Sisa pelunasan sebesar <strong>{{ rupiah($order->remaining_balance) }}</strong> akan ditagihkan saat pesanan selesai dan siap dikirim.
                @else
                    Pembayaran pesanan telah dikonfirmasi dan lunas. Pesanan Anda sedang diproses oleh tim percetakan.
                @endif
            @elseif($order->status === 'completed')
                <strong class="font-bold block mb-0.5">Status: Pesanan Selesai</strong>
                Pesanan Anda telah selesai diproduksi dan dikirimkan atau diserahkan sesuai metode pengiriman yang dipilih. Terima kasih atas kepercayaan Anda!
            @elseif($order->status === 'cancelled')
                <strong class="font-bold block mb-0.5">Status: Pesanan Dibatalkan</strong>
                Pesanan ini telah dibatalkan. Jika ada pertanyaan lebih lanjut, silakan hubungi tim kami melalui WhatsApp.
            @endif
        </div>
    </div>

    <!-- Status & Overview Box -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100 pb-4">
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Status Pesanan Saat Ini:</span>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge_color }}">
                    {{ $order->status_label }}
                </span>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Skema Pembayaran:</span>
                <span class="font-bold text-xs text-charcoal-900 block mt-1 uppercase">
                    {{ $order->payment_label }}
                </span>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Tanggal Acara:</span>
                <span class="font-bold text-xs text-charcoal-900 block mt-1">
                    {{ $order->event_date ? $order->event_date->locale('id')->translatedFormat('d F Y') : '-' }}
                </span>
            </div>
        </div>

        <!-- Item Products Snapshot Table -->
        <div>
            <h3 class="font-serif font-bold text-base text-charcoal-900 mb-3">
                Item Undangan Dipesan (Snapshot)
            </h3>
            <div class="border border-stone-100 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-ivory-100/70 border-b border-stone-200 text-stone-600 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">Nama Produk</th>
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-right">Harga Satuan</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-charcoal-900">{{ $item->product_name }}</span>
                                        @if($item->isWholesale())
                                            <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                                Harga Grosir
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">DP 50%: {{ rupiah($item->dp_price) }} / pcs</span>
                                </td>
                                <td class="py-3 px-4 text-center font-semibold">
                                    {{ $item->quantity }} pcs
                                </td>
                                <td class="py-3 px-4 text-right">
                                    {{ rupiah($item->unit_price) }}
                                </td>
                                <td class="py-3 px-4 text-right font-serif font-bold text-charcoal-900">
                                    {{ rupiah($item->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals Breakdown -->
        <div class="bg-ivory-50 rounded-2xl p-4 sm:p-5 border border-stone-200/80 space-y-2.5 text-xs">
            <div class="flex justify-between text-stone-600">
                <span>Subtotal Seluruh Item:</span>
                <span class="font-semibold text-charcoal-900">{{ rupiah($order->subtotal) }}</span>
            </div>

            <div class="flex justify-between items-center text-stone-600">
                <span>Ongkos Kirim:</span>
                <span class="font-semibold text-emerald-700 inline-flex items-center gap-1.5">
                    <span class="text-[10px] px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200 font-bold">Free Ongkir Se-Riau</span>
                    <span>Rp 0</span>
                </span>
            </div>

            @if($order->discount_total > 0)
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Potongan Kupon Promo ({{ $order->promo_code }}):</span>
                    <span>-{{ rupiah($order->discount_total) }}</span>
                </div>
            @endif

            @if($order->is_dp)
                <div class="p-3.5 rounded-xl bg-champagne-100/60 border border-champagne-200/90 space-y-1.5 my-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium text-charcoal-800">Uang Muka (DP 50%):</span>
                        <span class="font-bold text-champagne-800">{{ rupiah($order->dp_amount) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-stone-600">
                        <span>Sisa Tagihan Pelunasan:</span>
                        <span class="font-semibold text-stone-700">{{ rupiah($order->remaining_balance) }}</span>
                    </div>
                    <p class="text-[10px] text-stone-500 pt-1 leading-snug">
                        💡 Dilunasi saat pengerjaan undangan selesai sebelum proses kirim.
                    </p>
                </div>
            @endif

            <div class="flex justify-between items-baseline pt-2 border-t border-stone-200">
                <div>
                    <span class="font-serif font-bold text-base text-charcoal-900 block">
                        {{ $order->is_dp ? 'Total Tagihan DP Sekarang:' : 'Total Nilai Tagihan:' }}
                    </span>
                    <span class="text-[10px] text-champagne-700 font-medium">
                        Skema: {{ $order->payment_label }}
                    </span>
                </div>
                <span class="font-serif font-bold text-xl text-champagne-700">
                    {{ rupiah($order->total_price) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Data Pemesan & Pengiriman Box -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-subtle space-y-3 text-xs">
            <h3 class="font-serif font-bold text-base text-charcoal-900 border-b border-stone-100 pb-2">
                Informasi Pemesan
            </h3>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Nama:</span>
                <span class="font-semibold text-charcoal-900">{{ $order->customer_name }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Email:</span>
                <span class="font-semibold text-charcoal-900">{{ $order->customer_email }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Nomor WhatsApp:</span>
                <span class="font-semibold text-charcoal-900">{{ $order->customer_phone }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Instagram:</span>
                <span class="font-semibold text-charcoal-900">
                    {{ $order->instagram_account ? '@' . ltrim($order->instagram_account, '@') : '-' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-subtle space-y-3 text-xs">
            <h3 class="font-serif font-bold text-base text-charcoal-900 border-b border-stone-100 pb-2">
                Informasi Pengiriman & Catatan
            </h3>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Metode Pengiriman:</span>
                <span class="font-semibold text-charcoal-900">{{ $order->shipping_method }}</span>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Ongkos Kirim:</span>
                <span class="font-bold text-emerald-700 inline-flex items-center gap-1.5">
                    Rp 0 <span class="px-2 py-0.5 text-[9px] bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200 font-bold">Free Ongkir Seluruh Riau</span>
                </span>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Alamat Tujuan:</span>
                <p class="font-semibold text-charcoal-900 leading-relaxed whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>
            <div>
                <span class="text-stone-400 block text-[10px] uppercase">Catatan Khusus:</span>
                <p class="text-stone-600 italic">{{ $order->notes ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
