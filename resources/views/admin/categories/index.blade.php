<x-admin-layout>
    <x-slot:heading>Manajemen Kategori</x-slot:heading>

    <x-admin.page-header
        title="Daftar Kategori Produk"
        subtitle="Kelola klasifikasi paket undangan (Bronze, Silver, Gold, Diamond, Digital)."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.categories.create') }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kategori
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="w-full sm:w-80 flex items-center gap-2">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari nama kategori..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                >
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Categories Table -->
    <x-admin.table :pagination="$categories->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Kategori</th>
            <th class="px-6 py-3.5">Deskripsi</th>
            <th class="px-6 py-3.5">Jumlah Produk</th>
            <th class="px-6 py-3.5">Urutan</th>
            <th class="px-6 py-3.5">Status</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($categories as $category)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3.5">
                        <img
                            src="{{ $category->image_url }}"
                            alt="{{ $category->name }}"
                            class="w-12 h-12 rounded-xl object-cover border border-stone-200 shrink-0"
                        >
                        <div>
                            <span class="font-semibold text-charcoal-900 block">{{ $category->name }}</span>
                            <span class="text-xs text-stone-400 font-mono">{{ $category->slug }}</span>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4">
                    <p class="text-xs text-stone-600 line-clamp-2 max-w-sm">
                        {{ $category->description ?? '-' }}
                    </p>
                </td>

                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-charcoal-800">
                        {{ $category->products_count }} produk
                    </span>
                </td>

                <td class="px-6 py-4 font-mono text-sm text-stone-600">
                    {{ $category->sort_order }}
                </td>

                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$category->is_active" type="boolean" />
                </td>

                <td class="px-6 py-4 text-right space-x-2">
                    <a
                        href="{{ route('admin.categories.edit', $category) }}"
                        class="p-2 text-stone-400 hover:text-champagne-700 hover:bg-champagne-50 rounded-lg inline-block transition-colors"
                        title="Edit Kategori"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button
                        type="button"
                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.categories.destroy', $category) }}', title: 'Kategori {{ $category->name }}' })"
                        class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-colors"
                        title="Hapus Kategori"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Belum ada kategori terdaftar.
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete />
</x-admin-layout>
