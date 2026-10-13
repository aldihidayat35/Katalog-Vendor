<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage displays with 200 OK status.
     */
    public function test_homepage_returns_successful_response(): void
    {
        $category = Category::factory()->create(['name' => 'Gold Suite']);
        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Maharani Deluxe',
            'is_featured' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(setting('site_name', 'Undangin'));
        $response->assertSee('Maharani Deluxe');
    }

    /**
     * Test catalog displays only active products.
     */
    public function test_catalog_displays_only_active_products(): void
    {
        $category = Category::factory()->create();

        $activeProduct = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Aktif Mutiara',
            'is_active' => true,
        ]);

        $inactiveProduct = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Tersembunyi Rahasia',
            'is_active' => false,
        ]);

        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee($activeProduct->name);
        $response->assertDontSee($inactiveProduct->name);
    }

    /**
     * Test category filtering on catalog page.
     */
    public function test_catalog_category_filter_works(): void
    {
        $catBronze = Category::factory()->create(['slug' => 'bronze-paket', 'name' => 'Paket Bronze']);
        $catDiamond = Category::factory()->create(['slug' => 'diamond-paket', 'name' => 'Paket Diamond']);

        $productBronze = Product::factory()->create([
            'category_id' => $catBronze->id,
            'name' => 'Undangan Arunika Bronze',
        ]);

        $productDiamond = Product::factory()->create([
            'category_id' => $catDiamond->id,
            'name' => 'Undangan Sultan Diamond',
        ]);

        $response = $this->get('/katalog?kategori=bronze-paket');

        $response->assertStatus(200);
        $response->assertSee($productBronze->name);
        $response->assertDontSee($productDiamond->name);
    }

    /**
     * Test search query filtering on catalog page.
     */
    public function test_catalog_search_filter_works(): void
    {
        $product1 = Product::factory()->create([
            'name' => 'Undangan Velvet Mewah',
        ]);

        $product2 = Product::factory()->create([
            'name' => 'Undangan Digital Motion 3D',
        ]);

        $response = $this->get('/katalog?q=Velvet');

        $response->assertStatus(200);
        $response->assertSee('Undangan Velvet Mewah');
        $response->assertDontSee('Undangan Digital Motion 3D');
    }

    /**
     * Test type filtering on catalog page (printed vs digital).
     */
    public function test_catalog_type_filter_works(): void
    {
        $printed = Product::factory()->create([
            'name' => 'Undangan Cetak Fisik Ivory',
            'type' => 'printed',
        ]);

        $digital = Product::factory()->create([
            'name' => 'Undangan Digital Interaktif Web',
            'type' => 'digital',
        ]);

        $response = $this->get('/katalog?tipe=digital');

        $response->assertStatus(200);
        $response->assertSee('Undangan Digital Interaktif Web');
        $response->assertDontSee('Undangan Cetak Fisik Ivory');
    }

    /**
     * Test product detail page returns 200 with product information.
     */
    public function test_product_detail_page_returns_successful_response(): void
    {
        $category = Category::factory()->create(['name' => 'Silver Deluxe']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Amora Foil Suite',
            'slug' => 'amora-foil-suite',
            'price' => 7000,
            'discount_price' => 6000,
            'dp_price' => 300000,
            'description' => 'Detail isi paket lengkap dengan amplop vellum.',
            'specifications' => 'Kertas Aster 230 gsm foil emas.',
            'is_active' => true,
        ]);

        $response = $this->get('/produk/amora-foil-suite');

        $response->assertStatus(200);
        $response->assertSee('Amora Foil Suite');
        $response->assertSee('Silver Deluxe');
        $response->assertSee('Rp 6.000');
        $response->assertSee('Rp 300.000');
        $response->assertSee('Detail isi paket lengkap dengan amplop vellum.');
        $response->assertSee('Kertas Aster 230 gsm foil emas.');
        $response->assertSee('Chat Admin');
    }

    /**
     * Test inactive or nonexistent product returns 404.
     */
    public function test_inactive_or_nonexistent_product_returns_404(): void
    {
        $inactive = Product::factory()->create([
            'slug' => 'produk-tidak-aktif',
            'is_active' => false,
        ]);

        $responseInactive = $this->get('/produk/produk-tidak-aktif');
        $responseInactive->assertStatus(404);

        $responseNonexistent = $this->get('/produk/produk-tidak-ada-sama-sekali');
        $responseNonexistent->assertStatus(404);
    }

    /**
     * Test supporting pages return 200 OK.
     */
    public function test_supporting_pages_return_200(): void
    {
        $responseHowToOrder = $this->get('/cara-pesan');
        $responseHowToOrder->assertStatus(200);
        $responseHowToOrder->assertSee('Cara Pemesanan Undangan');

        $responseContact = $this->get('/kontak');
        $responseContact->assertStatus(200);
        $responseContact->assertSee('Hubungi Galeria Invitation');
    }

    /**
     * Test catalog filtering by theme color.
     */
    public function test_catalog_can_filter_by_theme_color(): void
    {
        $category = Category::factory()->create();

        $goldProduct = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Emas Keraton',
            'theme_color' => 'gold',
            'is_active' => true,
        ]);

        $sageProduct = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Hijau Sage Alam',
            'theme_color' => 'sage',
            'is_active' => true,
        ]);

        // Filter gold
        $responseGold = $this->get('/katalog?warna=gold');
        $responseGold->assertStatus(200);
        $responseGold->assertSee('Undangan Emas Keraton');
        $responseGold->assertDontSee('Undangan Hijau Sage Alam');

        // Filter sage
        $responseSage = $this->get('/katalog?warna=sage');
        $responseSage->assertStatus(200);
        $responseSage->assertSee('Undangan Hijau Sage Alam');
        $responseSage->assertDontSee('Undangan Emas Keraton');
    }
}
