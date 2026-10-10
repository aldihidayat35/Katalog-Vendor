@extends('layouts.app')

@section('title', 'Pesanan Berhasil Disimpan - ' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 text-center"
     x-data="{
         countdown: 2,
         redirected: false,
         waUrl: '{{ $waLink }}',
         openWhatsApp() {
             this.redirected = true;
             window.open(this.waUrl, '_blank');
         },
         init() {
             const timer = setInterval(() => {
                 this.countdown--;
                 if (this.countdown <= 0) {
                     clearInterval(timer);
                     this.openWhatsApp();
                 }
             }, 1000);
         }
     }">

    <!-- Success Icon -->
    <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-sm animate-bounce">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <span class="text-xs uppercase font-bold tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
        Pesanan Diterima (Lead)
    </span>

    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-charcoal-900 mt-3 mb-2">
        Data Pesanan Anda Sudah Tersimpan!
    </h1>
    <p class="text-sm text-stone-600 max-w-lg mx-auto mb-8 leading-relaxed">
        Nomor pesanan Anda adalah <strong class="text-charcoal-900 font-mono">{{ $order->order_number }}</strong>. Segera lanjutkan ke WhatsApp admin kami untuk konfirmasi draft desain dan nomor rekening pembayaran.
    </p>

    <!-- Auto-redirect Timer Info -->
    <div class="mb-8 p-4 rounded-2xl bg-ivory-100 border border-champagne-300/80 max-w-md mx-auto text-xs text-stone-600">
        <p x-show="countdown > 0" class="flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-champagne-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>Membuka WhatsApp secara otomatis dalam <strong x-text="countdown"></strong> detik...</span>
        </p>
        <p x-show="countdown <= 0" class="text-emerald-700 font-medium">
            Jendela WhatsApp telah dibuka. Jika popup diblokir oleh browser Anda, silakan klik tombol hijau di bawah.
        </p>
    </div>

    <!-- Big WhatsApp Button -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-10">
        <a
            href="{{ $waLink }}"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full sm:w-auto px-8 py-4 rounded-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-base shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-3 transition-all duration-200 hover:scale-105"
        >
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Lanjutkan ke WhatsApp Admin</span>
        </a>

        <a
            href="{{ route('order.show', $order) }}"
            class="w-full sm:w-auto px-6 py-4 rounded-full border border-stone-300 hover:bg-stone-50 text-charcoal-800 font-semibold text-sm transition-colors"
        >
            Lihat Rincian Pesanan
        </a>
    </div>

    <!-- Order Summary Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle text-left max-w-2xl mx-auto space-y-4">
        <div class="flex items-center justify-between border-b border-stone-100 pb-3">
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Nomor Pesanan</span>
                <span class="font-mono font-bold text-charcoal-900 text-base sm:text-lg">{{ $order->order_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Status</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $order->status_badge_color }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        <!-- Items Snapshot -->
        <div class="space-y-2 text-xs">
            @foreach($order->items as $item)
                <div class="flex justify-between py-1 border-b border-stone-50">
                    <div>
                        <span class="font-bold text-charcoal-900">{{ $item->product_name }}</span>
                        <span class="text-stone-500 block">({{ $item->quantity }} pcs × {{ rupiah($item->unit_price) }})</span>
                    </div>
                    <span class="font-serif font-bold text-charcoal-900 self-center">
                        {{ rupiah($item->subtotal) }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="pt-2 text-xs space-y-1.5 border-t border-stone-100">
            <div class="flex justify-between text-stone-600">
                <span>Subtotal</span>
                <span>{{ rupiah($order->subtotal) }}</span>
            </div>

            @if($order->discount_total > 0)
                <div class="flex justify-between text-emerald-600 font-medium">
                    <span>Diskon ({{ $order->promo_code }})</span>
                    <span>-{{ rupiah($order->discount_total) }}</span>
                </div>
            @endif

            <div class="flex justify-between items-baseline pt-2 border-t border-stone-200 text-sm">
                <div>
                    <span class="font-bold text-charcoal-900 block">Total Tagihan:</span>
                    <span class="text-[11px] text-champagne-700 font-medium capitalize">
                        Skema: {{ $order->payment_option === 'dp' ? 'Uang Muka (DP)' : 'Full Payment' }}
                    </span>
                </div>
                <span class="font-serif font-bold text-xl text-champagne-700">
                    {{ rupiah($order->total_price) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Catalog link -->
    <div class="mt-8">
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-900 underline">
            Kembali ke Katalog Desain
        </a>
    </div>
</div>
@endsection
