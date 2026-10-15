<x-app-layout 
    title="Panduan Cara Pemesanan Undangan Mudah dan Cepat"
    description="Panduan 3 langkah mudah memesan undangan pernikahan mewah, mulai dari pemilihan katalog, konsultasi preview via WhatsApp, hingga pengiriman aman ke seluruh Indonesia."
    keywords="cara pesan undangan, alur pemesanan undangan, order undangan online, konsultasi undangan pernikahan"
    :canonicalUrl="route('how-to-order')"
>
    <!-- Schema.org BreadcrumbList -->
    @push('schema')
    @php
        $orderBreadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Cara Pemesanan',
                    'item' => route('how-to-order'),
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($orderBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    <!-- Header Banner -->
    <div class="bg-charcoal text-white py-12 sm:py-16 border-b border-champagne-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase font-bold tracking-widest text-champagne-400 mb-2 block">
                PANDUAN LENGKAP
            </span>
            <h1 class="text-3xl sm:text-5xl font-serif font-bold text-white tracking-tight">
                Cara Pemesanan Undangan
            </h1>
            <p class="text-stone-300 text-sm sm:text-base max-w-2xl mx-auto mt-3 leading-relaxed">
                Alur pemesanan mudah, terstruktur, dan personal untuk mewujudkan undangan pernikahan terbaik Anda.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
        
        <!-- Detailed Steps Grid -->
        <div class="space-y-8 sm:space-y-12">
            
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-start gap-6 bg-white p-8 rounded-3xl border border-stone-200 shadow-subtle">
                <div class="w-14 h-14 rounded-2xl bg-champagne-500 text-white flex-shrink-0 flex items-center justify-center font-serif text-2xl font-bold shadow-md">
                    1
                </div>
                <div class="space-y-2 flex-1">
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700">Langkah Pertama</span>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pilih Model Desain di Katalog</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Buka halaman katalog dan pilih desain yang sesuai dengan tema pernikahan Anda. Kami menyediakan pilihan undangan cetak fisik (Bronze, Silver, Gold, Diamond) dan undangan digital responsif. Anda dapat menyaring berdasarkan kategori dan mengecek detail spesifikasi bahan.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row items-start gap-6 bg-white p-8 rounded-3xl border border-stone-200 shadow-subtle">
                <div class="w-14 h-14 rounded-2xl bg-champagne-500 text-white flex-shrink-0 flex items-center justify-center font-serif text-2xl font-bold shadow-md">
                    2
                </div>
                <div class="space-y-2 flex-1">
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700">Langkah Kedua</span>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Tentukan Jumlah &amp; Tambah ke Keranjang</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Tentukan jumlah undangan (minimal 100 pcs untuk undangan cetak, atau 1 untuk undangan digital). Anda juga dapat menegosiasikan opsi pembayaran Uang Muka (DP) atau termin langsung bersama admin di WhatsApp. Klik tombol <strong>Tambahkan ke Keranjang</strong>.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-start gap-6 bg-white p-8 rounded-3xl border border-stone-200 shadow-subtle">
                <div class="w-14 h-14 rounded-2xl bg-champagne-500 text-white flex-shrink-0 flex items-center justify-center font-serif text-2xl font-bold shadow-md">
                    3
                </div>
                <div class="space-y-2 flex-1">
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700">Langkah Ketiga</span>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Lengkapi Formulir Checkout &amp; Data Acara</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Masuk ke halaman keranjang belanja dan isi data diri calon pengantin, tanggal acara, alamat pengiriman, akun Instagram, serta metode ekspedisi pengiriman yang diinginkan.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row items-start gap-6 bg-white p-8 rounded-3xl border border-stone-200 shadow-subtle">
                <div class="w-14 h-14 rounded-2xl bg-champagne-500 text-white flex-shrink-0 flex items-center justify-center font-serif text-2xl font-bold shadow-md">
                    4
                </div>
                <div class="space-y-2 flex-1">
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700">Langkah Keempat</span>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Dialihkan Otomatis ke WhatsApp Admin</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Setelah menekan tombol Lanjutkan, sistem kami akan langsung membuka chat WhatsApp dengan format pesanan lengkap yang sudah terisi otomatis. Anda tidak perlu mengetik ulang rincian pesanan.
                    </p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col md:flex-row items-start gap-6 bg-white p-8 rounded-3xl border border-stone-200 shadow-subtle">
                <div class="w-14 h-14 rounded-2xl bg-champagne-500 text-white flex-shrink-0 flex items-center justify-center font-serif text-2xl font-bold shadow-md">
                    5
                </div>
                <div class="space-y-2 flex-1">
                    <span class="text-xs uppercase font-bold tracking-widest text-champagne-700">Langkah Kelima</span>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Penyusunan Draft, Revisi, &amp; Produksi</h2>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Tim desainer kami akan membuatkan preview digital draft undangan untuk diperiksa bersama hingga disetujui (ACC). Setelah ACC, pesanan masuk ke tahap cetak dan quality control sebelum dikirimkan ke alamat Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Box -->
        <div class="mt-16 bg-ivory-100 rounded-3xl p-8 sm:p-12 text-center border border-champagne-300">
            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-charcoal-900 mb-3">Siap Memulai Pemesanan?</h3>
            <p class="text-sm text-stone-600 max-w-lg mx-auto mb-8">
                Jelajahi koleksi terlengkap kami sekarang atau konsultasikan langsung kebutuhan Anda dengan tim admin kami.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <x-button href="{{ route('catalog.index') }}" variant="primary" size="lg">
                    Buka Katalog Undangan
                </x-button>
                <x-button href="{{ wa_link('Halo Admin, saya ingin bertanya tentang cara pemesanan undangan.') }}" target="_blank" rel="noopener" variant="whatsapp" size="lg">
                    Konsultasi WhatsApp
                </x-button>
            </div>
        </div>
    </div>
</x-app-layout>
