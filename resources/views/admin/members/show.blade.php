<x-admin-layout>
    <x-slot:heading>Detail Member: {{ $user->name }}</x-slot:heading>

    <x-admin.page-header
        title="Profil Member: {{ $user->name }}"
        subtitle="Informasi akun pengguna dan riwayat transaksi pesanan."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.members.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar Member
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Member Profile Card (1 Col) -->
        <div class="space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-5">
                <div class="flex items-center gap-4 border-b border-stone-100 pb-4">
                    <div class="w-14 h-14 rounded-2xl bg-champagne-100 text-champagne-800 flex items-center justify-center font-serif text-2xl font-bold shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-serif font-bold text-lg text-charcoal-900 truncate">{{ $user->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-800 mt-1">
                            Pelanggan Terdaftar
                        </span>
                    </div>
                </div>

                <div class="space-y-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Email</span>
                        <span class="font-medium text-charcoal-900">{{ $user->email }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">No. WhatsApp / HP</span>
                        <span class="font-medium text-charcoal-900">{{ $user->phone ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Akun Instagram</span>
                        <span class="font-medium text-champagne-700">{{ $user->instagram ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Alamat Pengiriman Tersimpan</span>
                        <p class="font-medium text-charcoal-900 mt-0.5 whitespace-pre-line">{{ $user->address ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Tanggal Bergabung</span>
                        <span class="text-stone-600">{{ $user->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200 text-xs text-stone-500">
                    <svg class="w-4 h-4 inline mr-1 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sesuai kebijakan keamanan, password member tidak dapat dilihat atau diubah secara langsung oleh admin.
                </div>
            </div>
        </div>

        <!-- Right: Order History (2 Cols) -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-lg font-serif font-bold text-charcoal-900">
                Riwayat Pesanan ({{ $orders->total() }})
            </h3>

            <x-admin.table :pagination="$orders->links()">
                <x-slot:header>
                    <th class="px-6 py-3.5">No. Pesanan</th>
                    <th class="px-6 py-3.5">Tanggal</th>
                    <th class="px-6 py-3.5">Total &amp; Opsi</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </x-slot:header>

                @forelse ($orders as $order)
                    <tr class="hover:bg-stone-50/70 transition-colors">
                        <td class="px-6 py-4 font-mono font-medium text-xs text-charcoal-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 text-xs text-stone-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-charcoal-900 block">{{ rupiah($order->total_price) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-admin.status-badge :status="$order->status" type="order" />
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-champagne-100 hover:text-champagne-900 transition-colors"
                            >
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-stone-400 text-sm">
                            Member ini belum memiliki riwayat pesanan.
                        </td>
                    </tr>
                @endforelse
            </x-admin.table>
        </div>
    </div>
</x-admin-layout>
