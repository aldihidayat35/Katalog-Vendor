<x-admin-layout>
    <x-slot:heading>Popup Promosi Landing Page</x-slot:heading>

    <x-admin.page-header
        title="Pengaturan Popup Promosi"
        subtitle="Kelola poster portrait promosi yang muncul otomatis saat pengunjung pertama kali membuka landing page."
    >
        <x-slot:actions>
            <a
                href="{{ route('home', ['preview_popup' => 1]) }}"
                target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-charcoal-900 border border-stone-200 hover:bg-stone-50 transition-colors shadow-2xs"
            >
                <svg class="w-4 h-4 text-champagne-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat di Landing Page &rarr;
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div
        x-data="{
            enabled: {{ $popup['enabled'] ? 'true' : 'false' }},
            title: '{{ addslashes($popup['title']) }}',
            linkType: '{{ $popup['link_type'] }}',
            customUrl: '{{ addslashes($popup['custom_url']) }}',
            waMessage: '{{ addslashes($popup['whatsapp_message']) }}',
            delaySeconds: '{{ $popup['delay_seconds'] }}',
            imagePreview: '{{ $popup['image_url'] }}',
            hasCustomImage: {{ $popup['image'] && !str_starts_with($popup['image'], 'images/') ? 'true' : 'false' }},
            removeImage: false,

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                        this.removeImage = false;
                    };
                    reader.readAsDataURL(file);
                }
            },

            resetToDefault() {
                this.imagePreview = '{{ asset('images/landing/promo-poster.svg') }}';
                this.removeImage = true;
                const fileInput = document.getElementById('popup_image_input');
                if (fileInput) fileInput.value = '';
            },

            get computedTargetLabel() {
                if (this.linkType === 'catalog') return 'Menuju Halaman Katalog (Semua Produk)';
                if (this.linkType === 'whatsapp') return 'Menuju Chat WhatsApp Admin Galeria';
                return this.customUrl ? this.customUrl : 'Menuju Custom URL';
            }
        }"
        class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
    >
        <!-- LEFT: FORM PENGATURAN POPUP (7 COLS) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
            <form
                method="POST"
                action="{{ route('admin.popup.update') }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">

                <!-- 1. TOGGLE STATUS AKTIF / NONAKTIF -->
                <div class="p-4 sm:p-5 rounded-2xl bg-ivory-100/70 border border-champagne-200 flex items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-charcoal-900 block mb-0.5">Status Popup Promosi</span>
                        <p class="text-[11px] text-stone-500 leading-snug">
                            Aktifkan agar popup otomatis tampil saat pengunjung pertama kali membuka landing page.
                        </p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input
                            type="checkbox"
                            name="popup_enabled"
                            value="1"
                            x-model="enabled"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-champagne-600"></div>
                    </label>
                </div>

                <!-- 2. JUDUL / LABEL PROMOSI -->
                <div>
                    <label for="popup_title" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Judul Promosi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="popup_title"
                        name="popup_title"
                        x-model="title"
                        required
                        placeholder="Contoh: Promo Spesial Musim Undangan"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-medium"
                    >
                    <p class="text-[11px] text-stone-400 mt-1">Digunakan untuk label internal admin dan alt-text gambar.</p>
                    @error('popup_title')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. UPLOAD GAMBAR POSTER PORTRAIT -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="popup_image_input" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Gambar Poster Portrait (Flyer) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-champagne-700 font-medium">
                            Disarankan rasio 3:4 atau 4:5
                        </span>
                    </div>

                    <input
                        type="file"
                        id="popup_image_input"
                        name="popup_image"
                        accept="image/jpeg,image/png,image/webp,image/svg+xml"
                        @change="previewImage($event)"
                        class="block w-full text-sm text-stone-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-champagne-50 file:text-champagne-700 hover:file:bg-champagne-100 cursor-pointer border border-stone-200 rounded-xl bg-stone-50/50"
                    >
                    <p class="text-[11px] text-stone-400 mt-1.5">
                        Mendukung format JPG, PNG, WEBP, atau SVG (maksimal 4 MB). Resolusi ideal: 600x800 px s/d 1080x1440 px.
                    </p>

                    <div class="mt-2.5 flex items-center gap-3">
                        <button
                            type="button"
                            @click="resetToDefault()"
                            x-show="hasCustomImage || imagePreview !== '{{ asset('images/landing/promo-poster.svg') }}'"
                            class="text-xs text-stone-500 hover:text-rose-600 underline font-medium"
                        >
                            Gunakan Poster Bawaan (Default Galeria)
                        </button>
                    </div>

                    @error('popup_image')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4. TUJUAN LINK SAAT POSTER DIKLIK -->
                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">
                        Tujuan Tautan Saat Poster Diklik <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Option: Catalog -->
                        <label
                            @click="linkType = 'catalog'"
                            :class="linkType === 'catalog' ? 'border-champagne-600 bg-champagne-50/70 ring-2 ring-champagne-500/40 text-charcoal-900 font-bold' : 'border-stone-200 bg-white text-stone-600 hover:bg-stone-50'"
                            class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between text-xs"
                        >
                            <div class="flex items-center gap-2 mb-1">
                                <input type="radio" name="popup_link_type" value="catalog" x-model="linkType" class="text-champagne-600 focus:ring-champagne-500">
                                <span class="font-semibold text-charcoal-900">Halaman Katalog</span>
                            </div>
                            <span class="text-[10px] text-stone-400 leading-tight">Mengarahkan ke daftar semua produk</span>
                        </label>

                        <!-- Option: WhatsApp -->
                        <label
                            @click="linkType = 'whatsapp'"
                            :class="linkType === 'whatsapp' ? 'border-champagne-600 bg-champagne-50/70 ring-2 ring-champagne-500/40 text-charcoal-900 font-bold' : 'border-stone-200 bg-white text-stone-600 hover:bg-stone-50'"
                            class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between text-xs"
                        >
                            <div class="flex items-center gap-2 mb-1">
                                <input type="radio" name="popup_link_type" value="whatsapp" x-model="linkType" class="text-champagne-600 focus:ring-champagne-500">
                                <span class="font-semibold text-charcoal-900">WhatsApp Admin</span>
                            </div>
                            <span class="text-[10px] text-stone-400 leading-tight">Chat langsung dengan pesan promo</span>
                        </label>

                        <!-- Option: Custom URL -->
                        <label
                            @click="linkType = 'custom'"
                            :class="linkType === 'custom' ? 'border-champagne-600 bg-champagne-50/70 ring-2 ring-champagne-500/40 text-charcoal-900 font-bold' : 'border-stone-200 bg-white text-stone-600 hover:bg-stone-50'"
                            class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between text-xs"
                        >
                            <div class="flex items-center gap-2 mb-1">
                                <input type="radio" name="popup_link_type" value="custom" x-model="linkType" class="text-champagne-600 focus:ring-champagne-500">
                                <span class="font-semibold text-charcoal-900">Custom URL</span>
                            </div>
                            <span class="text-[10px] text-stone-400 leading-tight">Link bebas / landing page lain</span>
                        </label>
                    </div>

                    <!-- Conditional Input: WhatsApp Message Template -->
                    <div x-show="linkType === 'whatsapp'" x-transition class="pt-2">
                        <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                            Template Pesan WhatsApp Otomatis
                        </label>
                        <textarea
                            name="popup_whatsapp_message"
                            x-model="waMessage"
                            rows="2"
                            placeholder="Halo Admin Galeria Invitation, saya melihat promo di website dan ingin konsultasi promo ini."
                            class="w-full text-xs rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 leading-relaxed"
                        ></textarea>
                    </div>

                    <!-- Conditional Input: Custom URL -->
                    <div x-show="linkType === 'custom'" x-transition class="pt-2">
                        <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                            Masukkan URL Tujuan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="url"
                            name="popup_custom_url"
                            x-model="customUrl"
                            placeholder="https://example.com/promo-khusus"
                            class="w-full text-xs rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                        >
                        @error('popup_custom_url')
                            <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 5. WAKTU TUNDA (DELAY SECONDS) -->
                <div class="pt-2 border-t border-stone-100">
                    <label for="popup_delay_seconds" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Waktu Tunda Kemunculan Popup (Detik) <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <select
                            id="popup_delay_seconds"
                            name="popup_delay_seconds"
                            x-model="delaySeconds"
                            class="w-48 text-xs rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-semibold"
                        >
                            <option value="0">0 Detik (Langsung Muncul)</option>
                            <option value="1">1.0 Detik</option>
                            <option value="1.5">1.5 Detik (Direkomendasikan)</option>
                            <option value="2">2.0 Detik</option>
                            <option value="3">3.0 Detik</option>
                        </select>
                        <span class="text-xs text-stone-500">
                            Memberikan waktu landing page selesai dirender sebelum popup muncul dengan efek halus.
                        </span>
                    </div>
                    @error('popup_delay_seconds')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end">
                    <button
                        type="submit"
                        class="px-8 py-3.5 rounded-full text-xs font-bold bg-champagne-600 hover:bg-champagne-700 active:scale-95 text-white transition-all shadow-goldglow flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Pengaturan Popup</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT: LIVE INTERACTIVE PREVIEW SIMULATOR (5 COLS) -->
        <div class="lg:col-span-5 sticky top-28 space-y-4">
            <div class="bg-charcoal-900 text-white p-6 sm:p-7 rounded-3xl border border-stone-800 shadow-2xl">
                <div class="flex items-center justify-between border-b border-stone-800 pb-3 mb-5">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-champagne-400 block mb-0.5">
                            LIVE SIMULATOR
                        </span>
                        <h3 class="text-sm font-serif font-bold text-white">Pratinjau Tampilan Pengunjung</h3>
                    </div>

                    <span
                        :class="enabled ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40' : 'bg-stone-700 text-stone-400 border-stone-600'"
                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="enabled ? 'bg-emerald-400 animate-pulse' : 'bg-stone-400'"></span>
                        <span x-text="enabled ? 'Aktif' : 'Nonaktif'"></span>
                    </span>
                </div>

                <!-- Mockup Backdrop & Modal Poster Container -->
                <div class="relative bg-black/60 backdrop-blur-md rounded-2xl p-4 sm:p-6 border border-white/10 flex flex-col items-center justify-center min-h-[460px]">
                    <!-- Floating Close Button Preview -->
                    <div class="relative w-full max-w-[280px]">
                        <button
                            type="button"
                            class="absolute -top-3 -right-3 z-20 w-8 h-8 rounded-full bg-charcoal-900/90 text-white flex items-center justify-center shadow-lg border border-white/30 cursor-default"
                            title="Tombol Tutup (X)"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <!-- Poster Card -->
                        <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl border border-white/20 relative group bg-stone-900">
                            <img
                                :src="imagePreview"
                                :alt="title"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            >

                            <!-- Hover Overlay Indicator -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4 text-center">
                                <span class="px-4 py-2 rounded-full bg-champagne-600 text-white text-[11px] font-bold shadow-lg">
                                    Klik Poster Untuk Membuka Tautan &rarr;
                                </span>
                            </div>
                        </div>

                        <!-- Checkbox: Jangan Tampilkan Lagi Hari Ini (Simulasi) -->
                        <div class="mt-3 flex items-center justify-center gap-2 text-[11px] text-stone-300 select-none">
                            <input type="checkbox" checked disabled class="rounded border-white/40 text-champagne-600 bg-black/40 focus:ring-0">
                            <span>Jangan tampilkan lagi hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- Simulation Footer Info -->
                <div class="mt-4 pt-3 border-t border-stone-800 text-[11px] text-stone-400 space-y-1.5">
                    <div class="flex items-start gap-2">
                        <span class="text-champagne-400 font-semibold shrink-0">Tautan Tujuan:</span>
                        <span class="text-stone-300 font-medium truncate" x-text="computedTargetLabel"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-champagne-400 font-semibold shrink-0">Waktu Tunda:</span>
                        <span class="text-stone-300 font-medium" x-text="delaySeconds + ' Detik setelah halaman terbuka'"></span>
                    </div>
                </div>
            </div>

            <!-- Hint Box -->
            <div class="p-4 rounded-2xl bg-champagne-50 border border-champagne-200 text-stone-700 text-xs leading-relaxed">
                <strong class="font-bold text-champagne-900 block mb-1">💡 Tips Desain Poster:</strong>
                Gunakan poster portrait dengan gambar tajam, headline promo yang memikat, diskon persentase, dan batas waktu promo untuk meningkatkan konversi pesanan dari pengunjung baru.
            </div>
        </div>
    </div>
</x-admin-layout>
