<x-admin-layout>
    <x-slot:heading>Manajemen Produk</x-slot:heading>

    <x-admin.page-header
        title="Daftar Produk Undangan"
        subtitle="Kelola desain undangan pernikahan cetak & digital, harga, stok, dan galeri foto."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.products.create') }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Cari Produk</label>
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Nama atau deskripsi..."
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                    >
                    <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Kategori</label>
                <select name="category_id" class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock Status Filter -->
            <div>
                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Status Stok</label>
                <select name="stock_status" class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                    <option value="">Semua Status</option>
                    <option value="ready" @selected(request('stock_status') === 'ready')>Ready Stock</option>
                    <option value="preorder" @selected(request('stock_status') === 'preorder')>Pre-order</option>
                    <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Stok Habis</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'category_id', 'stock_status', 'is_active']))
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-xl border border-stone-300 text-stone-600 text-sm font-semibold hover:bg-stone-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <x-admin.table :pagination="$products->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Produk</th>
            <th class="px-6 py-3.5">Kategori &amp; Tipe</th>
            <th class="px-6 py-3.5">Harga &amp; DP</th>
            <th class="px-6 py-3.5">Stok</th>
            <th class="px-6 py-3.5">Status</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($products as $product)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <!-- Thumbnail & Name -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3.5">
                        <img
                            src="{{ $product->primary_image_url }}"
                            alt="{{ $product->name }}"
                            class="w-14 h-14 rounded-xl object-cover border border-stone-200 shrink-0"
                        >
                        <div class="min-w-0">
                            <a href="{{ route('admin.products.edit', $product) }}" class="font-semibold text-charcoal-900 hover:text-champagne-700 transition-colors block truncate max-w-xs">
                                {{ $product->name }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-stone-400 font-mono">{{ $product->slug }}</span>
                                @if ($product->is_featured)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-champagne-100 text-champagne-800">
                                        Unggulan
                                    </span>
                                @endif
                                @if ($product->badge)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-stone-100 text-stone-700">
                                        {{ $product->badge }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>

                <!-- Category & Type -->
                <td class="px-6 py-4">
                    <span class="font-medium text-charcoal-900 block">{{ $product->category->name ?? '-' }}</span>
                    <div class="flex items-center flex-wrap gap-1.5 mt-1">
                        <span class="text-xs text-stone-500 uppercase tracking-wider">
                            {{ $product->type === 'printed' ? 'Cetak Fisik' : 'Undangan Digital' }}
                        </span>
                        @if ($product->theme_color && $product->theme_color_data)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-stone-100 text-stone-700 border border-stone-200/80" title="Tema Warna: {{ $product->theme_color_label }}">
                                <span class="w-2 h-2 rounded-full border border-black/10 shrink-0" style="background-color: {{ $product->theme_color_hex }};"></span>
                                <span>{{ $product->theme_color_label }}</span>
                            </span>
                        @endif
                    </div>
                </td>

                <!-- Price -->
                <td class="px-6 py-4">
                    <div class="space-y-0.5">
                        <div class="font-semibold text-charcoal-900">
                            {{ rupiah($product->final_price) }}
                        </div>
                        @if ($product->has_discount)
                            <div class="text-xs text-stone-400 line-through">
                                {{ rupiah($product->price) }}
                            </div>
                        @endif
                    </div>
                </td>

                <!-- Stock Status -->
                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$product->stock_status" type="stock" />
                    <span class="block text-[11px] text-stone-400 mt-1">Min. order: {{ $product->min_order }} pcs</span>
                </td>

                <!-- Active Status -->
                <td class="px-6 py-4">
                    <x-admin.status-badge :status="$product->is_active" type="boolean" />
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right space-x-2">
                    <a
                        href="{{ route('product.show', $product->slug) }}"
                        target="_blank"
                        class="p-2 text-stone-400 hover:text-charcoal hover:bg-stone-100 rounded-lg inline-block transition-colors"
                        title="Lihat di Katalog Publik"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                    <a
                        href="{{ route('admin.products.edit', $product) }}"
                        class="p-2 text-stone-400 hover:text-champagne-700 hover:bg-champagne-50 rounded-lg inline-block transition-colors"
                        title="Edit Produk"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button
                        type="button"
                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.products.destroy', $product) }}', title: '{{ $product->name }}' })"
                        class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-colors"
                        title="Hapus Produk"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Tidak ada produk ditemukan.
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <!-- Delete Confirmation Modal -->
    <x-confirm-delete />
</x-admin-layout>
