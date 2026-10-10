<x-admin-layout>
    <x-slot:heading>Edit Kategori Undangan</x-slot:heading>

    <x-admin.page-header
        title="Edit Kategori: {{ $category->name }}"
        subtitle="Perbarui nama paket, deskripsi, urutan, atau ubah gambar sampul."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.categories.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-2xl bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
        <form
            method="POST"
            action="{{ route('admin.categories.update', $category) }}"
            enctype="multipart/form-data"
            x-data="{
                name: '{{ old('name', addslashes($category->name)) }}',
                slug: '{{ old('slug', $category->slug) }}',
                imagePreview: null,
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.imagePreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            }"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Nama Kategori <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    name="name"
                    x-model="name"
                    value="{{ old('name', $category->name) }}"
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
                    value="{{ old('slug', $category->slug) }}"
                    required
                    class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 bg-stone-50/50"
                >
                @error('slug')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Deskripsi Kategori</label>
                <textarea
                    name="description"
                    rows="3"
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Ganti Gambar Sampul (Opsional)</label>
                
                @if ($category->image_path)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $category->image_url }}" class="w-16 h-16 rounded-2xl object-cover border border-stone-200">
                        <span class="text-xs text-stone-500">Gambar saat ini. Pilih file baru untuk mengganti.</span>
                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    @change="previewImage($event)"
                    class="block w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-champagne-50 file:text-champagne-700 hover:file:bg-champagne-100 cursor-pointer"
                >
                @error('image')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror

                <template x-if="imagePreview">
                    <div class="mt-3">
                        <p class="text-xs text-stone-500 mb-1">Preview gambar baru:</p>
                        <img :src="imagePreview" class="w-24 h-24 rounded-2xl object-cover border border-champagne-300">
                    </div>
                </template>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Urutan Tampilan <span class="text-rose-500">*</span></label>
                <input
                    type="number"
                    name="sort_order"
                    value="{{ old('sort_order', $category->sort_order) }}"
                    min="0"
                    required
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >
                @error('sort_order')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- is_active toggle -->
            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $category->is_active))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Aktifkan Kategori</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                <x-button href="{{ route('admin.categories.index') }}" variant="outline" size="md">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" size="md">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
