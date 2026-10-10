<x-admin-layout>
    <x-slot:heading>Dashboard Admin</x-slot:heading>

    <x-admin.page-header
        title="Ringkasan Operasional"
        subtitle="Pantau pesanan masuk, performa katalog produk, dan aktivitas pelanggan terkini."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.products.create') }}" variant="primary" size="sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Produk
            </x-button>
            <x-button href="{{ route('admin.orders.index') }}" variant="outline" size="sm">
                Lihat Semua Pesanan
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- 1. STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <!-- New Leads -->
        <x-admin.stat-card
            title="Lead Baru"
            :value="$stats['new_leads']"
            description="Menunggu follow-up WhatsApp"
            color="amber"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Orders This Month -->
        <x-admin.stat-card
            title="Pesanan Bulan Ini"
            :value="$stats['monthly_orders']"
            description="Total checkout bulan ini"
            color="blue"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Revenue This Month -->
        <x-admin.stat-card
            title="Nilai Pesanan Bulan Ini"
            :value="rupiah($stats['monthly_revenue'])"
            description="Akumulasi omset pesanan"
            color="emerald"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Products (Active / Inactive) -->
        <x-admin.stat-card
            title="Total Produk"
            :value="$stats['total_products']"
            :description="$stats['active_products'] . ' aktif / ' . $stats['inactive_products'] . ' nonaktif'"
            color="champagne"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>

        <!-- Total Members -->
        <x-admin.stat-card
            title="Total Member"
            :value="$stats['total_members']"
            description="Pengguna terdaftar"
            color="purple"
        >
            <x-slot:icon>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </x-slot:icon>
        </x-admin.stat-card>
    </div>

    <!-- 2. RECENT LEADS & RECENT PRODUCTS TABLES -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Leads (2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">Pesanan Masuk Terbaru (Leads)</h3>
                    <p class="text-xs text-stone-500">Pelanggan yang baru saja melakukan checkout katalog</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <x-admin.table>
                <x-slot:header>
                    <th class="px-5 py-3">No. Pesanan</th>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">Opsi</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </x-slot:header>

                @forelse ($recentLeads as $lead)
                    <tr class="hover:bg-stone-50/70 transition-colors">
                        <td class="px-5 py-4 font-mono font-medium text-xs text-charcoal-900">
                            {{ $lead->order_number }}
                            <span class="block text-[11px] text-stone-400 font-sans">{{ $lead->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-charcoal-900 block">{{ $lead->customer_name }}</span>
                            <span class="text-xs text-stone-500 block">{{ $lead->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs uppercase font-semibold px-2 py-0.5 rounded bg-stone-100 text-stone-700">
                                {{ $lead->payment_option }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-charcoal-900">
                            {{ rupiah($lead->total_price) }}
                        </td>
                        <td class="px-5 py-4">
                            <x-admin.status-badge :status="$lead->status" type="order" />
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $lead) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-champagne-100 hover:text-champagne-800 transition-colors">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-stone-400 text-sm">
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </x-admin.table>
        </div>

        <!-- Recent Products (1 col) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">Produk Terbaru</h3>
                    <p class="text-xs text-stone-500">5 produk yang baru ditambahkan</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-champagne-700 hover:text-champagne-800">
                    Semua &rarr;
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-subtle divide-y divide-stone-100 overflow-hidden">
                @forelse ($recentProducts as $prod)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-stone-50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover border border-stone-200 shrink-0">
                            <div class="min-w-0">
                                <h4 class="text-sm font-semibold text-charcoal-900 truncate">{{ $prod->name }}</h4>
                                <p class="text-xs text-stone-500">{{ $prod->category->name ?? 'Tanpa Kategori' }}</p>
                                <p class="text-xs font-bold text-champagne-700 mt-0.5">{{ rupiah($prod->final_price) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $prod) }}" class="p-2 rounded-lg text-stone-400 hover:text-charcoal hover:bg-stone-100 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400 text-sm">
                        Belum ada produk.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
