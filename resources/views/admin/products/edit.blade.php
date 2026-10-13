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
            tiers: {{ json_encode(old('tiers', $product->priceTiers->map(fn($t) => ['min_quantity' => $t->min_quantity, 'max_quantity' => $t->max_quantity, 'unit_price' => $t->unit_price])->toArray())) }},
            addTier() {
                let lastTier = this.tiers.length > 0 ? this.tiers[this.tiers.length - 1] : null;
                let nextMin = lastTier && lastTier.max_quantity ? parseInt(lastTier.max_quantity) + 1 : (lastTier && lastTier.min_quantity ? parseInt(lastTier.min_quantity) + 100 : 100);
                this.tiers.push({ min_quantity: nextMin, max_quantity: '', unit_price: '' });
            },
            removeTier(index) {
                this.tiers.splice(index, 1);
            },
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

                <!-- Theme Color Selector -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Tema Warna Utama
                        <span class="text-stone-400 font-normal lowercase">(opsional &mdash; filter katalog)</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                        <label class="relative flex items-center gap-2.5 p-2 rounded-xl border border-stone-200 cursor-pointer hover:bg-stone-50 transition-all has-[:checked]:border-champagne-500 has-[:checked]:bg-champagne-50/50 has-[:checked]:ring-1 has-[:checked]:ring-champagne-500">
                            <input type="radio" name="theme_color" value="" @checked(empty(old('theme_color', $product->theme_color))) class="text-champagne-600 focus:ring-champagne-500">
                            <span class="w-4 h-4 rounded-full border border-stone-300 bg-stone-100 flex-shrink-0"></span>
                            <span class="text-xs text-stone-600 truncate">Tanpa Warna</span>
                        </label>
                        @foreach (\App\Models\Product::THEME_COLORS as $key => $color)
                            <label class="relative flex items-center gap-2.5 p-2 rounded-xl border border-stone-200 cursor-pointer hover:bg-stone-50 transition-all has-[:checked]:border-champagne-500 has-[:checked]:bg-champagne-50/50 has-[:checked]:ring-1 has-[:checked]:ring-champagne-500">
                                <input type="radio" name="theme_color" value="{{ $key }}" @checked(old('theme_color', $product->theme_color) === $key) class="text-champagne-600 focus:ring-champagne-500">
                                <span class="w-4 h-4 rounded-full border border-black/10 flex-shrink-0 shadow-xs" style="background-color: {{ $color['hex'] }};"></span>
                                <span class="text-xs font-medium text-charcoal-800 truncate" title="{{ $color['name'] }}">{{ $color['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('theme_color')
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

        <!-- 3. SKEMA HARGA GROSIR (TIERED PRICING) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-stone-100 pb-3">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">
                        3. Skema Harga Grosir / Bertingkat (Opsional)
                    </h3>
                    <p class="text-xs text-stone-500 mt-0.5">
                        Atur harga satuan berbeda berdasarkan total kuantitas pemesanan (contoh: 1–100 pcs: Rp 1.000, 101–200 pcs: Rp 800, ≥201 pcs: Rp 700).
                    </p>
                </div>
                <button
                    type="button"
                    @click="addTier()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-champagne-50 text-champagne-800 hover:bg-champagne-100 border border-champagne-200 transition-colors shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tingkatan Grosir
                </button>
            </div>

            <template x-if="tiers.length === 0">
                <div class="text-center py-6 px-4 bg-stone-50/70 rounded-2xl border border-dashed border-stone-200">
                    <p class="text-xs text-stone-500">
                        Belum ada tingkatan harga grosir. Produk ini akan menggunakan harga satuan normal di atas.
                    </p>
                    <button
                        type="button"
                        @click="addTier()"
                        class="mt-2 text-xs font-semibold text-champagne-700 hover:underline"
                    >
                        + Tambah Tingkatan Grosir Pertama
                    </button>
                </div>
            </template>

            <div x-show="tiers.length > 0" class="space-y-3">
                <div class="hidden sm:grid grid-cols-12 gap-3 text-[11px] font-bold text-stone-500 uppercase tracking-wider px-2">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-3">Min Qty (pcs) <span class="text-rose-500">*</span></div>
                    <div class="col-span-4">Max Qty (pcs, kosongkan = tanpa batas)</div>
                    <div class="col-span-3">Harga Satuan (Rp/pcs) <span class="text-rose-500">*</span></div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <template x-for="(tier, idx) in tiers" :key="idx">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center p-3 sm:p-2 bg-stone-50/70 sm:bg-transparent rounded-2xl sm:rounded-none border sm:border-b sm:border-stone-100 sm:border-t-0 sm:border-x-0">
                        <div class="col-span-1 text-center font-bold text-xs text-stone-400">
                            <span class="sm:inline" x-text="idx + 1"></span>
                        </div>
                        <div class="col-span-3">
                            <label class="block sm:hidden text-[11px] font-semibold text-stone-500 mb-1">Min Qty (pcs) *</label>
                            <input
                                type="number"
                                :name="'tiers[' + idx + '][min_quantity]'"
                                x-model.number="tier.min_quantity"
                                min="1"
                                required
                                placeholder="Contoh: 1"
                                class="w-full text-xs rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                            >
                        </div>
                        <div class="col-span-4">
                            <label class="block sm:hidden text-[11px] font-semibold text-stone-500 mb-1">Max Qty (pcs)</label>
                            <input
                                type="number"
                                :name="'tiers[' + idx + '][max_quantity]'"
                                x-model.number="tier.max_quantity"
                                :min="tier.min_quantity || 1"
                                placeholder="Kosongkan jika tidak terbatas"
                                class="w-full text-xs rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                            >
                        </div>
                        <div class="col-span-3">
                            <label class="block sm:hidden text-[11px] font-semibold text-stone-500 mb-1">Harga Satuan (Rp/pcs) *</label>
                            <input
                                type="number"
                                :name="'tiers[' + idx + '][unit_price]'"
                                x-model.number="tier.unit_price"
                                min="0"
                                required
                                placeholder="Contoh: 800"
                                class="w-full text-xs font-semibold text-charcoal-900 rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                            >
                        </div>
                        <div class="col-span-1 text-center">
                            <button
                                type="button"
                                @click="removeTier(idx)"
                                class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors"
                                title="Hapus tingkatan"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- 4. GALERI FOTO PRODUK -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                4. Galeri Foto Produk Saat Ini
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
