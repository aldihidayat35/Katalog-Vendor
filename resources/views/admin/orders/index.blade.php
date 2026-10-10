<x-admin-layout>
    <x-slot:heading>Manajemen Pesanan (Leads)</x-slot:heading>

    <x-admin.page-header
        title="Daftar Pesanan &amp; Leads Masuk"
        subtitle="Pantau data checkout pelanggan, update status tindak lanjut WhatsApp, dan catat progres pesanan."
    >
        <x-slot:actions>
            <x-button
                href="{{ route('admin.orders.export', request()->query()) }}"
                variant="outline"
                size="md"
            >
                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Search & Filters Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Cari Pesanan</label>
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="No. invoice, nama, email, atau HP..."
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                    >
                    <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Status Pesanan</label>
                <select name="status" class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                    <option value="">Semua Status</option>
                    <option value="lead" @selected(request('status') === 'lead')>Lead Baru</option>
                    <option value="contacted" @selected(request('status') === 'contacted')>Sudah Dihubungi</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Dikonfirmasi</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Dibatalkan</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div>
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors shrink-0">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('admin.orders.index') }}" class="p-2.5 rounded-xl border border-stone-300 text-stone-500 hover:bg-stone-50 transition-colors shrink-0" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <x-admin.table :pagination="$orders->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">No. Pesanan</th>
            <th class="px-6 py-3.5">Data Pemesan</th>
            <th class="px-6 py-3.5">Tanggal Acara</th>
            <th class="px-6 py-3.5">Total &amp; Opsi</th>
            <th class="px-6 py-3.5">Status</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($orders as $order)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <!-- Order Number & Created At -->
                <td class="px-6 py-4">
                    <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-sm text-champagne-700 hover:text-champagne-800">
                        {{ $order->order_number }}
                    </a>
                    <span class="block text-xs text-stone-400 mt-0.5">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                </td>

                <!-- Customer Info -->
                <td class="px-6 py-4">
                    <span class="font-semibold text-charcoal-900 block">{{ $order->customer_name }}</span>
                    <span class="text-xs text-stone-500 block">{{ $order->customer_phone }}</span>
                    @if ($order->instagram_account)
                        <span class="text-xs text-stone-400 block">{{ $order->instagram_account }}</span>
                    @endif
                </td>

                <!-- Event Date -->
                <td class="px-6 py-4 text-xs text-stone-600">
                    @if ($order->event_date)
                        <span class="font-medium text-charcoal-900 block">{{ $order->event_date->translatedFormat('d F Y') }}</span>
                    @else
                        <span class="text-stone-400">-</span>
                    @endif
                </td>

                <!-- Total & Payment Option -->
                <td class="px-6 py-4">
                    <span class="font-semibold text-charcoal-900 block">{{ rupiah($order->total_price) }}</span>
                    <span class="text-xs uppercase font-semibold text-stone-500">
                        {{ $order->payment_option === 'dp' ? 'Uang Muka (DP)' : 'Full Payment' }}
                    </span>
                    @if ($order->promo_code)
                        <span class="block text-[11px] text-emerald-700 font-mono">Promo: {{ $order->promo_code }}</span>
                    @endif
                </td>

                <!-- Status -->
                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$order->status" type="order" />
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                    <a
                        href="{{ route('admin.orders.show', $order) }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-stone-100 text-stone-800 hover:bg-champagne-100 hover:text-champagne-900 transition-colors"
                    >
                        Lihat Detail &rarr;
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Tidak ada pesanan ditemukan sesuai kriteria filter.
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</x-admin-layout>
