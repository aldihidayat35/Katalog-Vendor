<x-app-layout>
    <x-slot:title>Hubungi Kami - {{ setting('site_name', 'Galeria Invitation') }}</x-slot:title>

    <!-- Header Banner -->
    <div class="bg-charcoal text-white py-12 sm:py-16 border-b border-champagne-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase font-bold tracking-widest text-champagne-400 mb-2 block">
                LAYANAN PELANGGAN
            </span>
            <h1 class="text-3xl sm:text-5xl font-serif font-bold text-white tracking-tight">
                Hubungi Galeria Invitation
            </h1>
            <p class="text-stone-300 text-sm sm:text-base max-w-2xl mx-auto mt-3 leading-relaxed">
                Kami siap membantu menjawab segala pertanyaan seputar katalog, spesifikasi bahan, sampel undangan, dan estimasi waktu produksi.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12">
            
            <!-- Contact Info Cards -->
            <div class="space-y-6">
                <!-- WhatsApp Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle flex items-start gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-bold tracking-wider text-stone-400">WhatsApp Resmi Admin</span>
                        <h3 class="font-serif text-xl font-bold text-charcoal-900 mt-0.5">
                            +{{ clean_phone_number(setting('whatsapp_number', '6281234567890')) }}
                        </h3>
                        <p class="text-xs text-stone-500 mt-1 leading-relaxed">
                            Kanal komunikasi utama untuk konsultasi desain, konfirmasi pesanan, dan update pengiriman.
                        </p>
                        <div class="mt-4">
                            <x-button
                                href="{{ wa_link('Halo Admin Galeria, saya ingin berkonsultasi mengenai pemesanan undangan.') }}"
                                target="_blank"
                                rel="noopener"
                                variant="whatsapp"
                                size="sm"
                            >
                                Kirim Pesan WhatsApp &rarr;
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle flex items-start gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-champagne-50 text-champagne-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-bold tracking-wider text-stone-400">Email Resmi</span>
                        <h3 class="font-serif text-xl font-bold text-charcoal-900 mt-0.5">
                            {{ setting('contact_email', 'halo@katalogundangan.com') }}
                        </h3>
                        <p class="text-xs text-stone-500 mt-1 leading-relaxed">
                            Untuk penawaran kerjasama vendor, media, atau pertanyaan bisnis lainnya.
                        </p>
                    </div>
                </div>

                <!-- Instagram Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-subtle flex items-start gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-bold tracking-wider text-stone-400">Portofolio Instagram</span>
                        <h3 class="font-serif text-xl font-bold text-charcoal-900 mt-0.5">
                            @galeria.invitation
                        </h3>
                        <p class="text-xs text-stone-500 mt-1 leading-relaxed">
                            Lihat dokumentasi real video undangan cetak dan testimoni klien kami.
                        </p>
                        <div class="mt-4">
                            <x-button
                                href="{{ setting('instagram_url', 'https://instagram.com') }}"
                                target="_blank"
                                rel="noopener"
                                variant="outline"
                                size="sm"
                            >
                                Kunjungi Instagram &rarr;
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operational & Workshop Info -->
            <div class="bg-ivory-100 rounded-3xl p-8 sm:p-10 border border-champagne-300 flex flex-col justify-between">
                <div>
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700 block mb-2">
                        JAM LAYANAN &amp; LOKASI
                    </span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-charcoal-900 mb-6">
                        Informasi Operasional Studio
                    </h2>

                    <div class="space-y-6 text-sm text-stone-600">
                        <div>
                            <h4 class="font-semibold text-charcoal-900 mb-1">Jam Konsultasi Online:</h4>
                            <p>Senin – Minggu: 09.00 – 21.00 WIB</p>
                            <p class="text-xs text-stone-500 mt-0.5">(Chat WhatsApp tetap dapat dikirimkan kapan saja, akan dibalas pada jam aktif)</p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-charcoal-900 mb-1">Jangkauan Pengiriman:</h4>
                            <p>Seluruh Indonesia melalui ekspedisi JNE, J&amp;T Express, serta kurir instan Gojek/Grab untuk area Jabodetabek.</p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-charcoal-900 mb-1">Pengambilan di Tempat (Self-Pickup):</h4>
                            <p>Workshop Galeria Invitation, Jakarta Selatan (Sesuai perjanjian jadwal pengambilan saat pesanan telah selesai).</p>
                        </div>
                    </div>
                </div>

                <div class="pt-8 mt-8 border-t border-champagne-200">
                    <p class="text-xs text-stone-500 leading-relaxed mb-4">
                        Ingin melihat sampel fisik bahan kertas dan aksesoris wax seal langsung? Hubungi kami untuk jadwal konsultasi offline.
                    </p>
                    <x-button
                        href="{{ wa_link('Halo Admin Galeria, saya ingin membuat janji untuk melihat sampel undangan fisik.') }}"
                        target="_blank"
                        rel="noopener"
                        variant="whatsapp"
                        size="md"
                        class="w-full"
                    >
                        Buat Janji Kunjungan via WA
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
