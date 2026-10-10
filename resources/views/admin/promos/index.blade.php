<x-admin-layout>
    <x-slot:heading>Manajemen Kupon &amp; Promo</x-slot:heading>

    <x-admin.page-header
        title="Daftar Kupon &amp; Promo"
        subtitle="Kelola kode voucher diskon pesanan, batasan kuota pemakaian, dan periode promosi."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.promos.create') }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Promo
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6">
        <form method="GET" action="{{ route('admin.promos.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full sm:w-auto">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari kode promo atau nama promo..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                >
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="w-full sm:w-48">
                <select name="status" class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif Sekarang</option>
                    <option value="expired" @selected(request('status') === 'expired')>Kedaluwarsa</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.promos.index') }}" class="px-4 py-2.5 rounded-xl border border-stone-300 text-stone-600 text-sm font-semibold hover:bg-stone-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Promos Table -->
    <x-admin.table :pagination="$promos->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Kode &amp; Nama Promo</th>
            <th class="px-6 py-3.5">Besaran Diskon</th>
            <th class="px-6 py-3.5">Ketentuan Belanja</th>
            <th class="px-6 py-3.5">Pemakaian</th>
            <th class="px-6 py-3.5">Periode Berlaku</th>
            <th class="px-6 py-3.5">Status</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($promos as $promo)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <!-- Code & Name -->
                <td class="px-6 py-4">
                    <span class="font-mono font-bold text-sm text-champagne-700 bg-champagne-50 px-2 py-0.5 rounded-md border border-champagne-200 inline-block mb-1">
                        {{ $promo->code }}
                    </span>
                    <span class="block text-xs font-semibold text-charcoal-900">{{ $promo->name }}</span>
                </td>

                <!-- Discount -->
                <td class="px-6 py-4 font-semibold text-charcoal-900">
                    @if ($promo->discount_type === 'percent')
                        <span class="text-emerald-700">{{ $promo->discount_amount }}%</span>
                        @if ($promo->max_discount)
                            <span class="block text-[11px] text-stone-400 font-normal">Maks: {{ rupiah($promo->max_discount) }}</span>
                        @endif
                    @else
                        <span class="text-emerald-700">{{ rupiah($promo->discount_amount) }}</span>
                    @endif
                </td>

                <!-- Minimum purchase -->
                <td class="px-6 py-4 text-xs text-stone-600">
                    @if ($promo->min_purchase > 0)
                        Min. belanja: <span class="font-medium text-charcoal-900">{{ rupiah($promo->min_purchase) }}</span>
                    @else
                        <span class="text-stone-400">Tanpa min. belanja</span>
                    @endif
                </td>

                <!-- Usage limit -->
                <td class="px-6 py-4">
                    <span class="font-semibold text-charcoal-900 text-sm">{{ $promo->used_count }}</span>
                    <span class="text-xs text-stone-400">/ {{ $promo->usage_limit ?? '∞' }} kali</span>
                </td>

                <!-- Date Period -->
                <td class="px-6 py-4 text-xs text-stone-600">
                    <span class="block">{{ $promo->start_date->format('d M Y') }}</span>
                    <span class="text-stone-400">s/d {{ $promo->end_date->format('d M Y') }}</span>
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$promo->computed_status" type="promo" />
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right space-x-2">
                    <a
                        href="{{ route('admin.promos.edit', $promo) }}"
                        class="p-2 text-stone-400 hover:text-champagne-700 hover:bg-champagne-50 rounded-lg inline-block transition-colors"
                        title="Edit Promo"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button
                        type="button"
                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.promos.destroy', $promo) }}', title: 'Kode Promo {{ $promo->code }}' })"
                        class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-colors"
                        title="Hapus Promo"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Belum ada kupon promo terdaftar.
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete />
</x-admin-layout>
