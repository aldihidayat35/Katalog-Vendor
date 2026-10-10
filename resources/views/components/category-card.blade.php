@props([
    'category',
])

<a
    href="{{ route('catalog.index', ['kategori' => $category->slug]) }}"
    class="group relative flex flex-col justify-between bg-white rounded-3xl p-6 border border-stone-200/80 hover:border-champagne-400 hover:shadow-elegant transition-all duration-300"
>
    <div>
        <div class="flex items-center justify-between mb-4">
            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-champagne-50 text-champagne-800 border border-champagne-200">
                Paket Koleksi
            </span>
            @if(isset($category->products_count))
                <span class="text-xs text-stone-400 font-medium">
                    {{ $category->products_count }} Model
                </span>
            @endif
        </div>

        <h3 class="text-2xl font-serif font-bold text-charcoal-900 group-hover:text-champagne-700 transition-colors mb-2">
            {{ $category->name }}
        </h3>

        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed line-clamp-3 mb-6">
            {{ $category->description }}
        </p>
    </div>

    <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
        <span class="text-xs font-bold text-champagne-700 group-hover:text-champagne-900 uppercase tracking-wider flex items-center gap-1.5 transition-colors">
            Lihat Koleksi
            <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </span>
    </div>
</a>
