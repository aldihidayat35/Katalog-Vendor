<x-app-layout>
    <x-slot:title>Katalog Undangan Pernikahan</x-slot:title>

    <!-- Page Banner Header -->
    <div class="bg-charcoal text-white py-12 sm:py-16 border-b border-champagne-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs uppercase font-bold tracking-widest text-champagne-400 mb-2 block">
                KOLEKSI LENGKAP
            </span>
            <h1 class="text-3xl sm:text-5xl font-serif font-bold text-white tracking-tight">
                Katalog Undangan Pernikahan
            </h1>
            <p class="text-stone-300 text-sm sm:text-base max-w-2xl mx-auto mt-3 leading-relaxed">
                Temukan beragam pilihan desain cetak fisik eksklusif dan undangan website digital modern berkualitas tinggi.
            </p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        
        <!-- Filter & Search Bar Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-subtle mb-10">
            <form method="GET" action="{{ route('catalog.index') }}" class="space-y-6">
                
                <!-- Search & Sort Row -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Search Input -->
                    <div class="relative w-full md:max-w-md">
                        <input
                            type="text"
                            name="q"
                            value="{{ $searchQuery }}"
                            placeholder="Cari nama desain, jenis amplop, atau bahan..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-full border-stone-300 bg-stone-50 text-sm focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500 placeholder:text-stone-400"
                        />
                        <svg class="w-5 h-5 text-stone-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        @if($searchQuery)
                            <a href="{{ route('catalog.index', array_merge(request()->except('q'))) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 text-xs">
                                &times;
                            </a>
                        @endif
                    </div>

                    <!-- Sort Select & Submit Button -->
                    <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                        <label for="sort" class="text-xs font-semibold text-stone-500 uppercase tracking-wider hidden sm:inline-block">
                            Urutkan:
                        </label>
                        <select
                            name="sort"
                            id="sort"
                            onchange="this.form.submit()"
                            class="rounded-full border-stone-300 bg-stone-50 text-xs font-medium text-charcoal-800 py-2.5 px-4 focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500"
                        >
                            <option value="terbaru" @selected($selectedSort === 'terbaru')>Terbaru</option>
                            <option value="harga_terendah" @selected($selectedSort === 'harga_terendah')>Harga Terendah</option>
                            <option value="harga_tertinggi" @selected($selectedSort === 'harga_tertinggi')>Harga Tertinggi</option>
                        </select>

                        <x-button type="submit" variant="primary" size="md" class="hidden sm:inline-flex">
                            Terapkan
                        </x-button>
                    </div>
                </div>

                <!-- Category Chips Filter -->
                <div class="pt-2 border-t border-stone-100">
                    <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-3">Kategori Paket:</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['kategori', 'page']))) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ empty($selectedCategory) ? 'bg-champagne text-white shadow-sm' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                        >
                            Semua Kategori
                        </a>

                        @foreach($categories as $cat)
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['page']), ['kategori' => $cat->slug])) }}"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ $selectedCategory === $cat->slug ? 'bg-champagne text-white shadow-sm' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                            >
                                {{ $cat->name }}
                                <span class="text-[10px] opacity-75">({{ $cat->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Product Type Chips Filter (Printed vs Digital) -->
                <div class="pt-2 border-t border-stone-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider mr-2">Tipe:</span>
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['tipe', 'type', 'page']))) }}"
                            class="px-3.5 py-1 rounded-full text-xs font-medium {{ empty($selectedType) ? 'bg-charcoal text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                        >
                            Semua Tipe
                        </a>
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['page']), ['tipe' => 'printed'])) }}"
                            class="px-3.5 py-1 rounded-full text-xs font-medium {{ $selectedType === 'printed' ? 'bg-charcoal text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                        >
                            Undangan Cetak
                        </a>
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['page']), ['tipe' => 'digital'])) }}"
                            class="px-3.5 py-1 rounded-full text-xs font-medium {{ $selectedType === 'digital' ? 'bg-charcoal text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}"
                        >
                            Undangan Digital
                        </a>
                    </div>

                    <!-- Reset Filters Button if any active -->
                    @if($selectedCategory || $searchQuery || $selectedType || $selectedSort !== 'terbaru')
                        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset Semua Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Products Grid / Empty State -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-stone-200 max-w-lg mx-auto shadow-subtle">
                <div class="w-16 h-16 rounded-full bg-champagne-50 text-champagne-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-serif text-2xl font-bold text-charcoal-900 mb-2">Produk Tidak Ditemukan</h3>
                <p class="text-sm text-stone-500 leading-relaxed mb-6">
                    Maaf, tidak ada produk yang cocok dengan kriteria pencarian atau filter yang Anda pilih.
                </p>
                <div class="flex justify-center gap-3">
                    <x-button href="{{ route('catalog.index') }}" variant="primary" size="md">
                        Reset Semua Filter
                    </x-button>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
