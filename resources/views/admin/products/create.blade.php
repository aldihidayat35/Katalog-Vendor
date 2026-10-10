<x-admin-layout>
    <x-slot:heading>Tambah Produk Undangan</x-slot:heading>

    <x-admin.page-header
        title="Tambah Produk Baru"
        subtitle="Lengkapi data spesifikasi, harga, status stok, dan unggah foto galeri produk."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.products.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <form
        method="POST"
        action="{{ route('admin.products.store') }}"
        enctype="multipart/form-data"
        x-data="{
            name: '{{ old('name') }}',
            slug: '{{ old('slug') }}',
            isSlugManual: false,
            price: {{ old('price', 0) }},
            discountPrice: '{{ old('discount_price', '') }}',
            dpPrice: {{ old('dp_price', 0) }},
            generateSlug() {
                if (!this.isSlugManual) {
                    this.slug = this.name.toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            },
            recalculateDp() {
                let base = (this.discountPrice && parseInt(this.discountPrice) > 0) ? parseInt(this.discountPrice) : parseInt(this.price || 0);
                this.dpPrice = Math.round(base * 0.5);
            },
            previews: [],
            primaryIndex: 0,
            handleFileSelect(event) {
                this.previews = [];
                const files = event.target.files;
                if (!files) return;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({
                            url: e.target.result,
                            name: files[i].name,
                            size: (files[i].size / 1024).toFixed(1) + ' KB'
                        });
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }"
        class="space-y-8"
    >
        @csrf

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
                        @input="generateSlug()"
                        value="{{ old('name') }}"
                        required
                        placeholder="Contoh: Royal Velvet Gold Invitation"
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
                        @input="isSlugManual = true"
                        value="{{ old('slug') }}"
                        required
                        placeholder="royal-velvet-gold-invitation"
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
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
                        <option value="printed" @selected(old('type', 'printed') === 'printed')>Cetak Fisik</option>
                        <option value="digital" @selected(old('type') === 'digital')>Digital / Video / Website</option>
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
                            <input type="radio" name="theme_color" value="" @checked(empty(old('theme_color'))) class="text-champagne-600 focus:ring-champagne-500">
                            <span class="w-4 h-4 rounded-full border border-stone-300 bg-stone-100 flex-shrink-0"></span>
                            <span class="text-xs text-stone-600 truncate">Tanpa Warna</span>
                        </label>
                        @foreach (\App\Models\Product::THEME_COLORS as $key => $color)
                            <label class="relative flex items-center gap-2.5 p-2 rounded-xl border border-stone-200 cursor-pointer hover:bg-stone-50 transition-all has-[:checked]:border-champagne-500 has-[:checked]:bg-champagne-50/50 has-[:checked]:ring-1 has-[:checked]:ring-champagne-500">
                                <input type="radio" name="theme_color" value="{{ $key }}" @checked(old('theme_color') === $key) class="text-champagne-600 focus:ring-champagne-500">
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
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Deskripsi Singkat (Ringkasan Katalog)</label>
                <textarea
                    name="short_description"
                    rows="2"
                    placeholder="Deskripsi singkat yang tampil pada kartu katalog..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('short_description') }}</textarea>
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
                    placeholder="Jelaskan isi paket undangan, kelengkapan kartu ucapan, amplop, dll..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Specifications -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Spesifikasi Teknis (Bahan, Ukuran, Finishing)</label>
                <textarea
                    name="specifications"
                    rows="3"
                    placeholder="Bahan: Jasmine Gold 260gr, Ukuran: 15x20cm lipat 2, Foil Hotprint Emas..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('specifications') }}</textarea>
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
                        value="{{ old('price', 0) }}"
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
                        value="{{ old('discount_price') }}"
                        min="0"
                        placeholder="Kosongkan jika tidak ada"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Harus lebih kecil dari harga normal</span>
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
                        value="{{ old('dp_price', 0) }}"
                        min="0"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Maksimal sebesar harga final produk</span>
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
                        value="{{ old('min_order', 100) }}"
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
                        <option value="ready" @selected(old('stock_status', 'ready') === 'ready')>Ready Stock</option>
                        <option value="preorder" @selected(old('stock_status') === 'preorder')>Pre-order (PO)</option>
                        <option value="out_of_stock" @selected(old('stock_status') === 'out_of_stock')>Stok Habis (Out of Stock)</option>
                    </select>
                    @error('stock_status')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Badge -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Badge Promosi (Opsional)</label>
                    <input
                        type="text"
                        name="badge"
                        value="{{ old('badge') }}"
                        placeholder="Contoh: BEST SELLER, NEW, HOT"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('badge')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Toggles (is_featured, is_active) -->
            <div class="pt-4 border-t border-stone-100 flex flex-wrap gap-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured'))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Tampilkan sebagai Produk Unggulan</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', true))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Publikasikan Produk (Aktif)</span>
                </label>
            </div>
        </div>

        <!-- 3. GALERI FOTO PRODUK -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                3. Galeri Foto Produk (Multi-Upload)
            </h3>

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Pilih File Foto (Maks 2 MB per file, format JPG/PNG/WEBP)</label>
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

            <!-- Preview Grid -->
            <template x-if="previews.length > 0">
                <div class="mt-4">
                    <p class="text-xs text-stone-500 mb-3">Klik tombol pada foto untuk menjadikannya <strong>Foto Utama</strong>:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <template x-for="(item, index) in previews" :key="index">
                            <div class="relative group rounded-2xl overflow-hidden border-2 bg-stone-50 aspect-square flex flex-col justify-end p-2 transition-all"
                                 :class="primaryIndex === index ? 'border-champagne-500 shadow-md ring-2 ring-champagne-200' : 'border-stone-200'">
                                <img :src="item.url" class="absolute inset-0 w-full h-full object-cover">
                                <div class="relative z-10 bg-black/60 backdrop-blur-xs rounded-xl p-1.5 text-center text-[10px] text-white">
                                    <button
                                        type="button"
                                        @click="primaryIndex = index"
                                        class="w-full py-0.5 rounded font-semibold transition-colors"
                                        :class="primaryIndex === index ? 'bg-champagne-500 text-white' : 'hover:bg-white/20'"
                                    >
                                        <span x-text="primaryIndex === index ? '★ Foto Utama' : 'Pilih Utama'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <input type="hidden" name="primary_image_index" :value="primaryIndex">
                </div>
            </template>
        </div>

        <!-- Form Submit Bar -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <x-button href="{{ route('admin.products.index') }}" variant="outline" size="lg">
                Batal
            </x-button>
            <x-button type="submit" variant="primary" size="lg">
                Simpan Produk Baru
            </x-button>
        </div>
    </form>
</x-admin-layout>
