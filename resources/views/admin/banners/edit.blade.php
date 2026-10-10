<x-admin-layout>
    <x-slot:heading>Edit Banner Slider</x-slot:heading>

    <x-admin.page-header
        title="Edit Banner: {{ $banner->title }}"
        subtitle="Perbarui gambar, judul headline, urutan, atau ubah status aktif banner."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.banners.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-2xl bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
        <form
            method="POST"
            action="{{ route('admin.banners.update', $banner) }}"
            enctype="multipart/form-data"
            x-data="{
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

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Judul Banner <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $banner->title) }}"
                    required
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >
                @error('title')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Subjudul (Keterangan Singkat)</label>
                <input
                    type="text"
                    name="subtitle"
                    value="{{ old('subtitle', $banner->subtitle) }}"
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >
                @error('subtitle')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Ganti Gambar Banner (Opsional)</label>
                
                @if ($banner->image_path)
                    <div class="mb-3">
                        <img src="{{ $banner->image_url }}" class="w-full max-h-40 rounded-2xl object-cover border border-stone-200">
                        <span class="text-xs text-stone-500 mt-1 block">Gambar saat ini. Pilih file baru di bawah untuk mengganti.</span>
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
                        <p class="text-xs text-stone-500 mb-1">Preview gambar pengganti:</p>
                        <img :src="imagePreview" class="w-full max-h-40 rounded-2xl object-cover border border-champagne-300 shadow-sm">
                    </div>
                </template>
            </div>

            <!-- Link URL -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tautan Tujuan (Link URL)</label>
                <input
                    type="text"
                    name="link_url"
                    value="{{ old('link_url', $banner->link_url) }}"
                    class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >
                @error('link_url')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Urutan Tampilan <span class="text-rose-500">*</span></label>
                <input
                    type="number"
                    name="sort_order"
                    value="{{ old('sort_order', $banner->sort_order) }}"
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
                        @checked(old('is_active', $banner->is_active))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Aktifkan dan Tampilkan Banner</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                <x-button href="{{ route('admin.banners.index') }}" variant="outline" size="md">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" size="md">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
