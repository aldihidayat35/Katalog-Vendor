@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - ' . setting('site_name', 'Galeria Invitation'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header -->
    <div class="mb-8 border-b border-champagne-200/60 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl font-bold text-charcoal-900">
                Riwayat Pesanan Saya
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 mt-1">
                Daftar seluruh pesanan dan status koordinasi undangan pernikahan Anda.
            </p>
        </div>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs shadow-sm self-start sm:self-auto transition-colors">
            <span>+ Pesan Desain Baru</span>
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-stone-200 shadow-subtle max-w-lg mx-auto my-12">
            <div class="w-16 h-16 bg-ivory-100 text-champagne-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="font-serif text-xl font-bold text-charcoal-900 mb-2">Belum Ada Pesanan</h2>
            <p class="text-stone-500 text-xs mb-6">
                Anda belum pernah mengirimkan pesanan undangan. Buka katalog untuk memilih koleksi desain favorit Anda.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs shadow-goldglow transition-colors">
                Mulai Lihat Katalog &rarr;
            </a>
        </div>
    @else
        <!-- Order List Table / Cards -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-subtle overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-ivory-100/70 border-b border-stone-200 text-stone-600 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-4 px-6">No. Pesanan</th>
                            <th class="py-4 px-6">Tanggal Pesan</th>
                            <th class="py-4 px-6">Item Produk</th>
                            <th class="py-4 px-6">Skema</th>
                            <th class="py-4 px-6">Total Tagihan</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-ivory-50/50 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-charcoal-900">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-4 px-6 text-stone-600">
                                    {{ $order->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-6 text-stone-700">
                                    {{ $order->items_count }} desain undangan
                                </td>
                                <td class="py-4 px-6 text-xs font-semibold {{ $order->is_dp ? 'text-champagne-700' : 'text-stone-600' }}">
                                    {{ $order->payment_label }}
                                </td>
                                <td class="py-4 px-6 font-serif font-bold text-sm text-champagne-700">
                                    {{ rupiah($order->total_price) }}
                                    @if($order->is_dp && $order->remaining_balance > 0)
                                        <span class="block text-[10px] font-sans font-normal text-stone-400">Sisa: {{ rupiah($order->remaining_balance) }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full font-semibold text-[11px] {{ $order->status_badge_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('order.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-ivory-100 hover:bg-champagne hover:text-white text-champagne-800 font-semibold transition-colors">
                                        <span>Rincian</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-6 border-t border-stone-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
