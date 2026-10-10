<x-admin-layout>
    <x-slot:heading>Pengaturan Situs</x-slot:heading>

    <x-admin.page-header
        title="Pengaturan Website &amp; Kontak"
        subtitle="Kelola identitas brand, nomor WhatsApp admin untuk checkout, metode pengiriman, dan pesan konsultasi."
    />

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
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
                1. Identitas Brand &amp; Saluran Kontak
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

        <!-- Form Submit Bar -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <x-button type="submit" variant="primary" size="lg">
                Simpan &amp; Terapkan Pengaturan
            </x-button>
        </div>
    </form>
</x-admin-layout>
