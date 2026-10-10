<x-admin-layout>
    <x-slot:heading>Edit Produk Undangan</x-slot:heading>

    <x-admin.page-header
        title="Edit Produk: {{ $product->name }}"
        subtitle="Perbarui spesifikasi, penyesuaian harga, status stok, atau tambahkan foto galeri."
    >
        <x-slot:actions>
            <x-button href="{{ route('product.show', $product->slug) }}" target="_blank" variant="secondary" size="md">
                Lihat di Publik
            </x-button>
            <x-button href="{{ route('admin.products.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <form
        method="POST"
        action="{{ route('admin.products.update', $product) }}"
        enctype="multipart/form-data"
        x-data="{
            name: '{{ old('name', addslashes($product->name)) }}',
            slug: '{{ old('slug', $product->slug) }}',
            price: {{ old('price', $product->price) }},
            discountPrice: '{{ old('discount_price', $product->discount_price ?? '') }}',
            dpPrice: {{ old('dp_price', $product->dp_price) }},
            recalculateDp() {
                let base = (this.discountPrice && parseInt(this.discountPrice) > 0) ? parseInt(this.discountPrice) : parseInt(this.price || 0);
                this.dpPrice = Math.round(base * 0.5);
            },
            previews: [],
            handleFileSelect(event) {
                this.previews = [];
                const files = event.target.files;
                if (!files) return;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({
                            url: e.target.result,
                            name: files[i].name
                        });
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }"
        class="space-y-8"
    >
        @csrf
        @method('PUT')

        <!-- 1. INFORMASI UTAMA & KATEGORI -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                1. Informasi Dasar &amp; Kategori
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Nama Produk <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        x-model="name"
                        value="{{ old('name', $product->name) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Slug URL <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="slug"
                        x-model="slug"
                        value="{{ old('slug', $product->slug) }}"
                        required
                        class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 bg-stone-50/50"
                    >
                    @error('slug')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tipe Undangan <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                        <option value="printed" @selected(old('type', $product->type) === 'printed')>Cetak Fisik</option>
                        <option value="digital" @selected(old('type', $product->type) === 'digital')>Digital / Video / Website</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Short description -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                <textarea
                    name="short_description"
                    rows="2"
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Full Description (Package content) -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Deskripsi Isi Paket &amp; Detail <span class="text-rose-500">*</span></label>
                <textarea
                    name="description"
                    rows="4"
                    required
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Specifications -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Spesifikasi Teknis</label>
                <textarea
                    name="specifications"
                    rows="3"
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('specifications', $product->specifications) }}</textarea>
                @error('specifications')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- 2. HARGA & ATURAN PEMESANAN -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                2. Penetapan Harga &amp; Aturan Pemesanan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Normal Price -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Harga Normal (Rp) <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        name="price"
                        x-model="price"
                        @input="recalculateDp()"
                        value="{{ old('price', $product->price) }}"
                        min="0"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('price')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Price -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Harga Diskon (Rp, Opsional)</label>
                    <input
                        type="number"
                        name="discount_price"
                        x-model="discountPrice"
                        @input="recalculateDp()"
                        value="{{ old('discount_price', $product->discount_price) }}"
                        min="0"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('discount_price')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DP Price -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">Harga DP per Pcs (Rp) <span class="text-rose-500">*</span></label>
                        <button type="button" @click="recalculateDp()" class="text-[11px] text-champagne-700 hover:underline">
                            Hitung 50%
                        </button>
                    </div>
                    <input
                        type="number"
                        name="dp_price"
                        x-model="dpPrice"
                        value="{{ old('dp_price', $product->dp_price) }}"
                        min="0"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('dp_price')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Min Order -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Minimal Pemesanan (Pcs) <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        name="min_order"
                        value="{{ old('min_order', $product->min_order) }}"
                        min="1"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('min_order')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Status -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Status Stok <span class="text-rose-500">*</span></label>
                    <select name="stock_status" required class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                        <option value="ready" @selected(old('stock_status', $product->stock_status) === 'ready')>Ready Stock</option>
                        <option value="preorder" @selected(old('stock_status', $product->stock_status) === 'preorder')>Pre-order (PO)</option>
                        <option value="out_of_stock" @selected(old('stock_status', $product->stock_status) === 'out_of_stock')>Stok Habis (Out of Stock)</option>
                    </select>
                    @error('stock_status')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Badge -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Badge Promosi</label>
                    <input
                        type="text"
                        name="badge"
                        value="{{ old('badge', $product->badge) }}"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('badge')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Toggles -->
            <div class="pt-4 border-t border-stone-100 flex flex-wrap gap-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured', $product->is_featured))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Tampilkan sebagai Produk Unggulan</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $product->is_active))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Publikasikan Produk (Aktif)</span>
                </label>
            </div>
        </div>

        <!-- 3. GALERI FOTO PRODUK -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                3. Galeri Foto Produk Saat Ini
            </h3>

            <!-- Existing Images List -->
            @if ($product->images->count() > 0)
                <div>
                    <p class="text-xs text-stone-500 mb-3">Pilih radio button untuk menentukan <strong>Foto Utama</strong>:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        @foreach ($product->images as $img)
                            <div class="relative group rounded-2xl overflow-hidden border-2 bg-stone-50 aspect-square flex flex-col justify-between p-2 {{ $img->is_primary ? 'border-champagne-500 shadow-md ring-2 ring-champagne-200' : 'border-stone-200' }}">
                                <img src="{{ $img->url }}" class="absolute inset-0 w-full h-full object-cover">
                                
                                <!-- Top: Delete image button -->
                                <div class="relative z-10 flex justify-end">
                                    <button
                                        type="button"
                                        form="delete-image-{{ $img->id }}"
                                        onclick="if(confirm('Hapus foto ini?')) { document.getElementById('delete-image-{{ $img->id }}').submit(); }"
                                        class="p-1 rounded-full bg-rose-600 text-white hover:bg-rose-700 shadow transition-colors"
                                        title="Hapus foto ini"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <!-- Bottom: Select as primary -->
                                <div class="relative z-10 bg-black/60 backdrop-blur-xs rounded-xl p-1 text-center text-[10px] text-white">
                                    <label class="flex items-center justify-center gap-1.5 cursor-pointer py-0.5">
                                        <input
                                            type="radio"
                                            name="primary_image_id"
                                            value="{{ $img->id }}"
                                            @checked($img->is_primary)
                                            class="text-champagne-600 focus:ring-champagne-500 w-3.5 h-3.5"
                                        >
                                        <span class="font-semibold">{{ $img->is_primary ? 'Foto Utama' : 'Jadikan Utama' }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-stone-400">Belum ada foto yang diunggah untuk produk ini.</p>
            @endif

            <!-- Additional Image Upload -->
            <div class="pt-4 border-t border-stone-100">
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Unggah Foto Tambahan (Maks 2 MB per file)</label>
                <input
                    type="file"
                    name="images[]"
                    multiple
                    accept="image/jpeg,image/png,image/webp"
                    @change="handleFileSelect($event)"
                    class="block w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-champagne-50 file:text-champagne-700 hover:file:bg-champagne-100 cursor-pointer"
                >
                @error('images.*')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Previews Grid -->
            <template x-if="previews.length > 0">
                <div class="mt-4">
                    <p class="text-xs text-stone-500 mb-2">Preview foto yang akan ditambahkan:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <template x-for="(item, index) in previews" :key="index">
                            <div class="relative rounded-2xl overflow-hidden border border-champagne-300 aspect-square">
                                <img :src="item.url" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Form Submit Bar -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <x-button href="{{ route('admin.products.index') }}" variant="outline" size="lg">
                Batal
            </x-button>
            <x-button type="submit" variant="primary" size="lg">
                Simpan Perubahan
            </x-button>
        </div>
    </form>

    <!-- Hidden image deletion forms -->
    @foreach ($product->images as $img)
        <form id="delete-image-{{ $img->id }}" action="{{ route('admin.products.images.destroy', $img) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</x-admin-layout>
