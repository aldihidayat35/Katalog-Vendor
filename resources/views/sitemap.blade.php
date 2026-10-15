{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- Halaman Utama & Statis -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('catalog.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('how-to-order') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Kategori Aktif -->
    @foreach ($categories as $cat)
        <url>
            <loc>{{ route('catalog.index', ['kategori' => $cat->slug]) }}</loc>
            <lastmod>{{ $cat->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            @if ($cat->image_url)
                <image:image>
                    <image:loc>{{ $cat->image_url }}</image:loc>
                    <image:title>{{ $cat->name }}</image:title>
                </image:image>
            @endif
        </url>
    @endforeach

    <!-- Produk Aktif -->
    @foreach ($products as $product)
        <url>
            <loc>{{ route('product.show', $product->slug) }}</loc>
            <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            @if ($product->primary_image_url)
                <image:image>
                    <image:loc>{{ $product->primary_image_url }}</image:loc>
                    <image:title>{{ $product->name }}</image:title>
                </image:image>
            @endif
        </url>
    @endforeach
</urlset>
