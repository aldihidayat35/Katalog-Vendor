<x-admin-layout>
    <x-slot:heading>Pengaturan Situs</x-slot:heading>

    <x-admin.page-header
        title="Pengaturan Website & Kontak"
        subtitle="Kelola identitas brand, nomor WhatsApp admin untuk checkout, metode pengiriman, dan pesan konsultasi."
    />

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
        x-data="{
            shippingMethods: {{ json_encode($settings['shipping_methods']) }},
            addShippingMethod() {
                this.shippingMethods.push({
                    id: '',
                    name: '',
                    desc: ''
                });
            },
            removeShippingMethod(index) {
                if (this.shippingMethods.length > 1) {
                    this.shippingMethods.splice(index, 1);
                } else {
                    alert('Minimal harus ada 1 metode pengiriman.');
                }
            }
        }"
        class="space-y-8"
    >
        @csrf
        @method('PUT')

        <!-- 1. IDENTITAS & KONTAK UTAMA -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                1. Identitas Brand, Logo &amp; Saluran Kontak
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Nama Website / Brand <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="site_name"
                        value="{{ old('site_name', $settings['site_name']) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('site_name')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tagline -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tagline Slogan <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="site_tagline"
                        value="{{ old('site_tagline', $settings['site_tagline']) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('site_tagline')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- WhatsApp Admin Number -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Nomor WhatsApp Admin (Checkout) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            name="whatsapp_number"
                            value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                            required
                            placeholder="081234567890 atau 6281234567890"
                            class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-10"
                        >
                        <span class="absolute left-3.5 top-2.5 text-stone-400 font-mono text-sm">📞</span>
                    </div>
                    <span class="text-[11px] text-stone-400 mt-1 block">Otomatis dinormalisasi ke format internasional (62...) untuk link wa.me</span>
                    @error('whatsapp_number')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Email Kontak Resmi <span class="text-rose-500">*</span></label>
                    <input
                        type="email"
                        name="contact_email"
                        value="{{ old('contact_email', $settings['contact_email']) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('contact_email')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instagram URL -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tautan Akun Instagram</label>
                    <input
                        type="url"
                        name="instagram_url"
                        value="{{ old('instagram_url', $settings['instagram_url']) }}"
                        placeholder="https://instagram.com/galeria.invitation"
                        class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('instagram_url')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Logo & Favicon Upload Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-stone-100">
                <!-- Site Logo -->
                <div x-data="{ logoPreview: '{{ site_logo_url() }}', removeLogo: false }">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Logo Utama Website
                    </label>
                    <div class="p-4 rounded-2xl border border-stone-200 bg-stone-50/50 space-y-3">
                        <div class="flex items-center gap-4">
                            <!-- Preview Box -->
                            <div class="w-16 h-16 rounded-2xl bg-white border border-stone-200 flex items-center justify-center p-2 shadow-sm overflow-hidden flex-shrink-0">
                                <template x-if="logoPreview && !removeLogo">
                                    <img :src="logoPreview" alt="Logo Preview" class="max-h-full max-w-full object-contain">
                                </template>
                                <template x-if="!logoPreview || removeLogo">
                                    <div class="w-10 h-10 rounded-full bg-champagne-500 text-white flex items-center justify-center font-serif text-lg font-bold">
                                        {{ substr(setting('site_name', 'G'), 0, 1) }}
                                    </div>
                                </template>
                            </div>
                            <!-- Upload Controls -->
                            <div class="flex-1 min-w-0">
                                <input
                                    type="file"
                                    name="site_logo"
                                    id="site_logo"
                                    accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                    class="block w-full text-xs text-stone-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-champagne-100 file:text-champagne-800 hover:file:bg-champagne-200 cursor-pointer"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            logoPreview = URL.createObjectURL(file);
                                            removeLogo = false;
                                        }
                                    "
                                >
                                <p class="text-[11px] text-stone-400 mt-1">Format: PNG, JPG, SVG, WEBP (Maks. 2MB). Ditampilkan di Navbar, Sidebar Admin, dan Footer.</p>
                            </div>
                        </div>

                        @if(setting('site_logo'))
                            <div class="pt-2 border-t border-stone-200/60 flex items-center justify-between">
                                <span class="text-xs text-stone-600 font-medium">Logo saat ini aktif</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-700 font-medium">
                                    <input type="checkbox" name="remove_logo" value="1" x-model="removeLogo" class="rounded border-stone-300 text-rose-600 focus:ring-rose-500">
                                    <span>Hapus Logo (Reset ke Bawaan)</span>
                                </label>
                            </div>
                        @endif
                    </div>
                    @error('site_logo')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Favicon -->
                <div x-data="{ faviconPreview: '{{ setting('site_favicon') ? site_favicon_url() : '' }}', removeFavicon: false }">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Favicon (Ikon Tab Browser)
                    </label>
                    <div class="p-4 rounded-2xl border border-stone-200 bg-stone-50/50 space-y-3">
                        <div class="flex items-center gap-4">
                            <!-- Preview Box -->
                            <div class="w-16 h-16 rounded-2xl bg-white border border-stone-200 flex items-center justify-center p-2 shadow-sm overflow-hidden flex-shrink-0">
                                <template x-if="faviconPreview && !removeFavicon">
                                    <img :src="faviconPreview" alt="Favicon Preview" class="w-8 h-8 object-contain">
                                </template>
                                <template x-if="!faviconPreview || removeFavicon">
                                    <span class="text-2xl">🌐</span>
                                </template>
                            </div>
                            <!-- Upload Controls -->
                            <div class="flex-1 min-w-0">
                                <input
                                    type="file"
                                    name="site_favicon"
                                    id="site_favicon"
                                    accept="image/x-icon,image/png,image/jpeg,image/svg+xml,image/webp"
                                    class="block w-full text-xs text-stone-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-champagne-100 file:text-champagne-800 hover:file:bg-champagne-200 cursor-pointer"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            faviconPreview = URL.createObjectURL(file);
                                            removeFavicon = false;
                                        }
                                    "
                                >
                                <p class="text-[11px] text-stone-400 mt-1">Format: ICO, PNG, SVG (Maks. 1MB). Ditampilkan di tab browser.</p>
                            </div>
                        </div>

                        @if(setting('site_favicon'))
                            <div class="pt-2 border-t border-stone-200/60 flex items-center justify-between">
                                <span class="text-xs text-stone-600 font-medium">Favicon saat ini aktif</span>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-rose-600 hover:text-rose-700 font-medium">
                                    <input type="checkbox" name="remove_favicon" value="1" x-model="removeFavicon" class="rounded border-stone-300 text-rose-600 focus:ring-rose-500">
                                    <span>Hapus Favicon (Reset ke Bawaan)</span>
                                </label>
                            </div>
                        @endif
                    </div>
                    @error('site_favicon')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. METODE PENGIRIMAN -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-900">2. Opsi Metode Pengiriman</h3>
                    <p class="text-xs text-stone-500">Pilihan kurir/ekspedisi yang tampil saat pelanggan checkout formulir pesanan</p>
                </div>
                <button
                    type="button"
                    @click="addShippingMethod()"
                    class="inline-flex items-center px-3 py-1.5 rounded-xl bg-champagne-100 text-champagne-800 text-xs font-semibold hover:bg-champagne-200 transition-colors"
                >
                    + Tambah Metode
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(method, index) in shippingMethods" :key="index">
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-full sm:w-1/3">
                            <label class="block text-[11px] font-semibold text-stone-500 uppercase mb-1">Nama Layanan</label>
                            <input
                                type="text"
                                :name="'shipping_methods[' + index + '][name]'"
                                x-model="method.name"
                                required
                                placeholder="Contoh: JNE Reguler / YES"
                                class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 bg-white"
                            >
                        </div>

                        <div class="w-full sm:flex-1">
                            <label class="block text-[11px] font-semibold text-stone-500 uppercase mb-1">Keterangan Singkat</label>
                            <input
                                type="text"
                                :name="'shipping_methods[' + index + '][desc]'"
                                x-model="method.desc"
                                placeholder="Contoh: Ekspedisi terpercaya ke seluruh Indonesia"
                                class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 bg-white"
                            >
                        </div>

                        <input type="hidden" :name="'shipping_methods[' + index + '][id]'" :value="method.id || method.name.toLowerCase().replace(/\s+/g, '-')">

                        <div class="pt-2 sm:pt-4">
                            <button
                                type="button"
                                @click="removeShippingMethod(index)"
                                class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors"
                                title="Hapus opsi ini"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- 3. TEMPLATE PESAN KONSULTASI -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-4">
            <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                3. Template Pesan Konsultasi WhatsApp
            </h3>
            <p class="text-xs text-stone-500">Teks sapaan awal saat pengunjung menekan tombol "Konsultasi" pada halaman utama atau header publik.</p>

            <div>
                <textarea
                    name="consultation_message"
                    rows="4"
                    required
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                >{{ old('consultation_message', $settings['consultation_message']) }}</textarea>
                @error('consultation_message')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- 4. PENGATURAN SEO & WEBMASTER GOOGLE -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6">
            <div>
                <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3">
                    4. Pengaturan SEO &amp; Webmaster Google
                </h3>
                <p class="text-xs text-stone-500 mt-1">Konfigurasi kata kunci, deskripsi pencarian Google, dan verifikasi Google Search Console agar website mudah ditemukan pembeli di seluruh Indonesia.</p>
            </div>

            <div class="space-y-4">
                <!-- Google Site Verification -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Kode Verifikasi Google Search Console (HTML Tag)
                    </label>
                    <input
                        type="text"
                        name="google_site_verification"
                        value="{{ old('google_site_verification', $settings['google_site_verification']) }}"
                        placeholder="Contoh: abcd1234efgh5678ijkl atau google-site-verification=..."
                        class="w-full text-sm font-mono rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">
                        Masukkan kode dari metode verifikasi "HTML Tag" Google Search Console. Otomatis disematkan di tag &lt;meta name="google-site-verification"&gt;.
                    </span>
                    @error('google_site_verification')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Keywords -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Kata Kunci Utama (SEO Meta Keywords)
                    </label>
                    <input
                        type="text"
                        name="seo_meta_keywords"
                        value="{{ old('seo_meta_keywords', $settings['seo_meta_keywords']) }}"
                        placeholder="undangan pernikahan, vendor undangan, cetak undangan mewah..."
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Pisahkan beberapa kata kunci dengan tanda koma (,).</span>
                    @error('seo_meta_keywords')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Deskripsi Cuplikan Google (SEO Meta Description Default)
                    </label>
                    <textarea
                        name="seo_meta_description"
                        rows="3"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                        placeholder="Tulis deskripsi rangkuman bisnis Anda (disarankan 120-160 karakter)..."
                    >{{ old('seo_meta_description', $settings['seo_meta_description']) }}</textarea>
                    <span class="text-[11px] text-stone-400 mt-1 block">Deskripsi ini akan tampil di bawah judul pada hasil pencarian Google jika halaman tidak memiliki deskripsi spesifik.</span>
                    @error('seo_meta_description')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Submit Bar -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <x-button type="submit" variant="primary" size="lg">
                Simpan &amp; Terapkan Pengaturan
            </x-button>
        </div>
    </form>
</x-admin-layout>
