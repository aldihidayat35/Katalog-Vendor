<x-app-layout>
    <x-slot:title>Katalog Undangan Pernikahan</x-slot:title>

    <!-- Page Banner Header Matching Landing Page Look -->
    <div class="pt-4 sm:pt-6">
        <div class="max-w-[1224px] w-[calc(100%-32px)] sm:w-[calc(100%-56px)] mx-auto rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-[#ece8e2]" style="background: radial-gradient(ellipse at 50% 30%, #3a281c 0%, #1e1510 100%);">
            <div class="py-10 sm:py-14 px-4 sm:px-8 text-center text-white">
                <span class="text-xs uppercase font-bold tracking-widest text-[#d89f66] mb-2 inline-block bg-white/10 px-3 py-1 rounded-full border border-white/10">
                    ✦ KOLEKSI LENGKAP
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-white tracking-tight">
                    Katalog Undangan Eksklusif
                </h1>
                <p class="text-stone-300 text-xs sm:text-sm max-w-2xl mx-auto mt-3 leading-relaxed">
                    Temukan beragam pilihan desain cetak fisik mewah dan undangan website digital modern berkualitas tinggi.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-[1224px] w-[calc(100%-32px)] sm:w-[calc(100%-56px)] mx-auto py-8 sm:py-12">
        
        <!-- Filter & Search Bar Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-subtle mb-10">
            @php
                $currentCategoryModel = $selectedCategory ? $categories->firstWhere('slug', $selectedCategory) : null;
                $currentColorData = $selectedColor && isset($themeColors[$selectedColor]) ? $themeColors[$selectedColor] : null;
                $hasActiveFilters = $selectedCategory || $searchQuery || $selectedType || $selectedColor || ($selectedSort && $selectedSort !== 'terbaru');
            @endphp

            <form method="GET" action="{{ route('catalog.index') }}" class="space-y-6">
                <!-- Hidden inputs to preserve filters when search or sort is submitted -->
                @if($selectedCategory)
                    <input type="hidden" name="kategori" value="{{ $selectedCategory }}">
                @endif
                @if($selectedType)
                    <input type="hidden" name="tipe" value="{{ $selectedType }}">
                @endif
                @if($selectedColor)
                    <input type="hidden" name="warna" value="{{ $selectedColor }}">
                @endif

                <!-- Search & Sort Row -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:max-w-lg">
                        <input
                            type="text"
                            name="q"
                            value="{{ $searchQuery }}"
                            placeholder="Cari nama desain, jenis amplop, atau bahan..."
                            class="w-full pl-11 pr-10 py-2.5 rounded-full border-stone-300 bg-stone-50/70 text-sm focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500 placeholder:text-stone-400 transition-colors"
                        />
                        <svg class="w-5 h-5 text-stone-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        @if($searchQuery)
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['q', 'page']))) }}"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-stone-200 hover:bg-stone-300 text-stone-600 flex items-center justify-center text-xs transition-colors"
                                title="Hapus pencarian"
                            >
                                &times;
                            </a>
                        @endif
                    </div>

                    <!-- Sort Select & Submit Button -->
                    <div class="flex items-center gap-3 justify-between md:justify-end">
                        <div class="flex items-center gap-2">
                            <label for="sort" class="text-xs font-semibold text-stone-500 uppercase tracking-wider hidden sm:inline-block">
                                Urutkan:
                            </label>
                            <select
                                name="sort"
                                id="sort"
                                onchange="this.form.submit()"
                                class="rounded-full border-stone-300 bg-stone-50 text-xs font-medium text-charcoal-800 py-2.5 pl-3.5 pr-8 focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500"
                            >
                                <option value="terbaru" @selected($selectedSort === 'terbaru')>Terbaru</option>
                                <option value="harga_terendah" @selected($selectedSort === 'harga_terendah')>Harga Terendah</option>
                                <option value="harga_tertinggi" @selected($selectedSort === 'harga_tertinggi')>Harga Tertinggi</option>
                            </select>
                        </div>

                        <span class="text-xs text-stone-500 hidden lg:inline-block pl-2 border-l border-stone-200">
                            Total: <strong class="text-charcoal-900 font-semibold">{{ $products->total() }}</strong> desain
                        </span>
                    </div>
                </div>

                <!-- 1. Format Undangan (Tipe) -->
                <div class="pt-4 border-t border-stone-100">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-1.5 h-3.5 bg-champagne-600 rounded-full inline-block"></span>
                        <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Format Undangan</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['tipe', 'type', 'page']))) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5 {{ empty($selectedType) ? 'bg-charcoal text-white font-semibold shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                        >
                            <span>Semua Format</span>
                        </a>
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['page']), ['tipe' => 'printed'])) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5 {{ $selectedType === 'printed' ? 'bg-charcoal text-white font-semibold shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $selectedType === 'printed' ? 'text-champagne-300' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span>Undangan Cetak Fisik</span>
                        </a>
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['page']), ['tipe' => 'digital'])) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5 {{ $selectedType === 'digital' ? 'bg-charcoal text-white font-semibold shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $selectedType === 'digital' ? 'text-blue-300' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Undangan Digital Web</span>
                        </a>
                    </div>
                </div>

                <!-- 2. Kategori Paket & Acara -->
                <div class="pt-4 border-t border-stone-100">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-3.5 bg-champagne-600 rounded-full inline-block"></span>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Kategori Paket &amp; Acara</span>
                        </div>
                        @if($selectedCategory)
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['kategori', 'category', 'page']))) }}"
                                class="text-[11px] font-semibold text-stone-400 hover:text-rose-600 transition-colors"
                            >
                                Hapus Kategori &times;
                            </a>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['kategori', 'category', 'page']))) }}"
                            class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all duration-200 {{ empty($selectedCategory) ? 'bg-champagne text-white font-semibold shadow-sm ring-1 ring-champagne-600/30' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                        >
                            Semua Kategori
                        </a>

                        @foreach($categories as $cat)
                            @php $isCatActive = $selectedCategory === $cat->slug; @endphp
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['page']), ['kategori' => $cat->slug])) }}"
                                class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-all duration-200 inline-flex items-center gap-1.5 {{ $isCatActive ? 'bg-champagne text-white font-semibold shadow-sm ring-1 ring-champagne-600/30' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                            >
                                <span>{{ $cat->name }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $isCatActive ? 'bg-white/25 text-white font-bold' : 'bg-stone-200/80 text-stone-600' }}">
                                    {{ $cat->products_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Tema Warna -->
                <div class="pt-4 border-t border-stone-100">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-3.5 bg-champagne-600 rounded-full inline-block"></span>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Tema Warna</span>
                        </div>
                        @if($selectedColor)
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['warna', 'color', 'page']))) }}"
                                class="text-[11px] font-semibold text-stone-400 hover:text-rose-600 transition-colors"
                            >
                                Hapus Warna &times;
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <a
                            href="{{ route('catalog.index', array_merge(request()->except(['warna', 'color', 'page']))) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all {{ empty($selectedColor) ? 'bg-charcoal text-white font-semibold shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                        >
                            <span class="w-2.5 h-2.5 rounded-full border border-stone-300 bg-stone-300"></span>
                            <span>Semua Warna</span>
                        </a>

                        @foreach($themeColors as $key => $color)
                            @php
                                $isActive = $selectedColor === $key;
                            @endphp
                            <a
                                href="{{ route('catalog.index', array_merge(request()->except(['page']), ['warna' => $key])) }}"
                                class="group/color inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs transition-all duration-200 {{ $isActive ? 'bg-charcoal text-white font-semibold shadow-sm ring-2 ring-champagne-400' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200/80 hover:border-stone-300' }}"
                                title="Filter tema: {{ $color['name'] }}"
                            >
                                <span
                                    class="w-3 h-3 rounded-full border border-black/15 shadow-2xs shrink-0 transition-transform group-hover/color:scale-110 {{ $isActive ? 'ring-1 ring-white' : '' }}"
                                    style="background-color: {{ $color['hex'] }};"
                                ></span>
                                <span class="text-xs">{{ $color['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Active Filters Summary & Reset Bar -->
                @if($hasActiveFilters)
                    <div class="pt-4 border-t border-stone-200/80 flex flex-wrap items-center justify-between gap-3 bg-stone-50/60 -mx-6 -mb-6 sm:-mx-8 sm:-mb-8 p-4 sm:p-5 rounded-b-3xl">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="font-bold text-stone-500 uppercase tracking-wider text-[10px] mr-1">Filter Aktif:</span>
                            
                            @if($searchQuery)
                                <a href="{{ route('catalog.index', array_merge(request()->except(['q', 'page']))) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white border border-stone-200 text-stone-700 text-xs hover:border-rose-300 hover:text-rose-600 transition-colors">
                                    <span>Kata kunci: "{{ Str::limit($searchQuery, 16) }}"</span>
                                    <span class="font-bold">&times;</span>
                                </a>
                            @endif

                            @if($selectedType)
                                <a href="{{ route('catalog.index', array_merge(request()->except(['tipe', 'type', 'page']))) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white border border-stone-200 text-stone-700 text-xs hover:border-rose-300 hover:text-rose-600 transition-colors">
                                    <span>Format: {{ $selectedType === 'printed' ? 'Cetak Fisik' : 'Digital Web' }}</span>
                                    <span class="font-bold">&times;</span>
                                </a>
                            @endif

                            @if($currentCategoryModel)
                                <a href="{{ route('catalog.index', array_merge(request()->except(['kategori', 'category', 'page']))) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-champagne-50 border border-champagne-200 text-champagne-800 text-xs hover:border-rose-300 hover:text-rose-600 transition-colors">
                                    <span>Kategori: {{ $currentCategoryModel->name }}</span>
                                    <span class="font-bold">&times;</span>
                                </a>
                            @endif

                            @if($currentColorData)
                                <a href="{{ route('catalog.index', array_merge(request()->except(['warna', 'color', 'page']))) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-stone-200 text-stone-700 text-xs hover:border-rose-300 hover:text-rose-600 transition-colors">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $currentColorData['hex'] }};"></span>
                                    <span>Warna: {{ $currentColorData['name'] }}</span>
                                    <span class="font-bold">&times;</span>
                                </a>
                            @endif
                        </div>

                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Reset Semua Filter
                        </a>
                    </div>
                @endif
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
