<x-admin-layout>
    <x-slot:heading>Manajemen Banner Slider</x-slot:heading>

    <x-admin.page-header
        title="Daftar Banner Homepage"
        subtitle="Kelola gambar slide promosi, headline penawaran, dan tautan promo di halaman utama."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.banners.create') }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Banner
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Banners Table -->
    <x-admin.table :pagination="$banners->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Gambar Banner</th>
            <th class="px-6 py-3.5">Judul &amp; Subjudul</th>
            <th class="px-6 py-3.5">Tautan Tujuan</th>
            <th class="px-6 py-3.5">Urutan</th>
            <th class="px-6 py-3.5">Status</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($banners as $banner)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <!-- Image Preview -->
                <td class="px-6 py-4">
                    <img
                        src="{{ $banner->image_url }}"
                        alt="{{ $banner->title }}"
                        class="w-32 h-16 rounded-xl object-cover border border-stone-200 shadow-xs"
                    >
                </td>

                <!-- Title & Subtitle -->
                <td class="px-6 py-4">
                    <span class="font-semibold text-charcoal-900 block">{{ $banner->title }}</span>
                    @if ($banner->subtitle)
                        <span class="text-xs text-stone-500 block mt-0.5">{{ $banner->subtitle }}</span>
                    @endif
                </td>

                <!-- Link -->
                <td class="px-6 py-4 text-xs font-mono text-stone-500">
                    {{ $banner->link_url ?? '-' }}
                </td>

                <!-- Sort Order -->
                <td class="px-6 py-4 font-mono text-sm text-stone-600">
                    {{ $banner->sort_order }}
                </td>

                <!-- Status -->
                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$banner->is_active" type="boolean" />
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right space-x-2">
                    <a
                        href="{{ route('admin.banners.edit', $banner) }}"
                        class="p-2 text-stone-400 hover:text-champagne-700 hover:bg-champagne-50 rounded-lg inline-block transition-colors"
                        title="Edit Banner"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button
                        type="button"
                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.banners.destroy', $banner) }}', title: 'Banner \'{{ $banner->title }}\'' })"
                        class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-colors"
                        title="Hapus Banner"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Belum ada banner promosi terdaftar.
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete />
</x-admin-layout>
