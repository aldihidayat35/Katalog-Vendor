<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoEnhancementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /**
     * 1. Test homepage national SEO meta tags & schemas.
     */
    public function test_homepage_renders_national_seo_meta_tags_and_schemas(): void
    {
        Setting::set('site_name', 'Galeria Undangan');
        Setting::set('site_tagline', 'Spesialis Undangan Pernikahan Mewah');
        Setting::set('seo_meta_description', 'Vendor spesialis undangan pernikahan mewah ke seluruh Indonesia.');
        Setting::set('seo_meta_keywords', 'undangan pernikahan, vendor undangan mewah, cetak undangan indonesia');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<meta name="robots" content="index, follow">', false);
        $response->assertSee('Vendor spesialis undangan pernikahan mewah ke seluruh Indonesia.');
        $response->assertSee('undangan pernikahan, vendor undangan mewah, cetak undangan indonesia');
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('og:site_name', false);
        $response->assertSee('twitter:card', false);

        // Schema.org assertions
        $response->assertSee('"@type": "WebSite"', false);
        $response->assertSee('"@type": "SearchAction"', false);
        $response->assertSee('"@type": "Organization"', false);
        $response->assertSee('"areaServed": "ID"', false);
    }

    /**
     * 2. Test catalog SEO tags and BreadcrumbList schema.
     */
    public function test_catalog_renders_dynamic_seo_and_breadcrumbs_schema(): void
    {
        $category = Category::factory()->create([
            'name' => 'Paket Velvet Gold',
            'slug' => 'paket-velvet-gold',
            'description' => 'Undangan dengan bahan beludru premium dan hotprint emas mewah.',
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Royal Velvet Suite',
            'is_active' => true,
        ]);

        // Generic catalog
        $response = $this->get('/katalog');
        $response->assertStatus(200);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('Beranda');
        $response->assertSee('Katalog');

        // Filtered by category
        $catResponse = $this->get('/katalog?kategori=' . $category->slug);
        $catResponse->assertStatus(200);
        $catResponse->assertSee('Koleksi Undangan Paket Velvet Gold');
        $catResponse->assertSee('Undangan dengan bahan beludru premium');
        $catResponse->assertSee('Paket Velvet Gold');
    }

    /**
     * 3. Test product detail SEO and Product + BreadcrumbList schemas.
     */
    public function test_product_detail_renders_product_and_breadcrumb_schemas(): void
    {
        $category = Category::factory()->create([
            'name' => 'Diamond Acrylic',
            'slug' => 'diamond-acrylic',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Imperial Acrylic Gold',
            'slug' => 'imperial-acrylic-gold',
            'price' => 25000,
            'discount_price' => 22000,
            'short_description' => 'Undangan akrilik kristal eksklusif 2mm gravir emas.',
            'stock_status' => 'ready',
            'is_active' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/sample-acrylic.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Imperial Acrylic Gold');
        $response->assertSee('Undangan akrilik kristal eksklusif 2mm gravir emas.');

        // Schema assertions
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('"@type": "Offer"', false);
        $response->assertSee('"priceCurrency": "IDR"', false);
        $response->assertSee('22000');
        $response->assertSee('https://schema.org/InStock');
        $response->assertSee('Diamond Acrylic');
    }

    /**
     * 4. Test sitemap.xml returns valid XML with Google Image extensions.
     */
    public function test_sitemap_xml_contains_google_image_extensions(): void
    {
        $category = Category::factory()->create([
            'slug' => 'undangan-elegan',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'undangan-elegan-01',
            'is_active' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/product-seo.jpg',
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', $response->headers->get('Content-Type'));
        $response->assertSee('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', false);
        $response->assertSee('<image:image>', false);
        $response->assertSee('<image:loc>', false);
        $response->assertSee(url('/katalog'), false);
        $response->assertSee(route('product.show', $product->slug), false);
    }

    /**
     * 5. Test robots.txt contains dynamic sitemap pointer.
     */
    public function test_robots_txt_contains_dynamic_sitemap_url(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertSee('User-agent: *');
        $response->assertSee('Allow: /');
        $response->assertSee('Disallow: /admin');
        $response->assertSee('Sitemap: ' . url('/sitemap.xml'));
    }

    /**
     * 6. Test admin settings can update and public view renders google site verification.
     */
    public function test_admin_settings_updates_and_public_renders_google_site_verification(): void
    {
        $token = 'google-verification-code-xyz-123456';

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'site_name' => 'Galeria Test',
            'site_tagline' => 'Tagline Test',
            'whatsapp_number' => '081234567890',
            'contact_email' => 'admin@galeria.test',
            'business_address' => 'Jakarta Barat',
            'consultation_message' => 'Halo Admin, saya ingin konsultasi undangan.',
            'google_site_verification' => $token,
            'seo_meta_keywords' => 'keyword a, keyword b',
            'seo_meta_description' => 'Deskripsi SEO Toko Baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals($token, Setting::get('google_site_verification'));
        $this->assertEquals('Deskripsi SEO Toko Baru', Setting::get('seo_meta_description'));

        // Visit public page and ensure the verification tag is rendered
        $publicResponse = $this->get('/');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('<meta name="google-site-verification" content="' . $token . '">', false);
    }

    /**
     * 7. Test static pages have SEO meta and breadcrumbs.
     */
    public function test_static_pages_have_seo_meta_and_breadcrumbs(): void
    {
        $orderPage = $this->get('/cara-pesan');
        $orderPage->assertStatus(200);
        $orderPage->assertSee('Panduan Cara Pemesanan Undangan');
        $orderPage->assertSee('"@type": "BreadcrumbList"', false);
        $orderPage->assertSee('Cara Pemesanan');

        // Test English redirect
        $this->get('/how-to-order')->assertRedirect('/cara-pesan');

        $contactPage = $this->get('/kontak');
        $contactPage->assertStatus(200);
        $contactPage->assertSee('Hubungi dan Konsultasi');
        $contactPage->assertSee('"@type": "BreadcrumbList"', false);
        $contactPage->assertSee('Hubungi Kami');

        // Test English redirect
        $this->get('/contact')->assertRedirect('/kontak');
    }
}
