<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
        ]);
    }

    /**
     * 1. Access Control Tests
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_regular_member_gets_403_forbidden(): void
    {
        $response = $this->actingAs($this->member)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Operasional');
        $response->assertSee('Tren Pemesanan Bulanan');
        $response->assertSee('Status Pesanan');
        $response->assertSee('monthlyTrendChart');
        $response->assertSee('orderStatusPieChart');
    }

    public function test_admin_dashboard_provides_analytics_api_endpoint(): void
    {
        $year = now()->year;

        Order::factory()->create([
            'status' => 'lead',
            'created_at' => Carbon::create($year, 1, 15, 12, 0, 0),
        ]);

        Order::factory()->create([
            'status' => 'confirmed',
            'created_at' => Carbon::create($year, 2, 20, 12, 0, 0),
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.dashboard.analytics', [
            'year' => $year,
            'month' => 'all',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'year',
                'month',
                'monthly_trend' => [
                    'labels',
                    'orders',
                    'revenue',
                    'total_orders',
                    'total_revenue',
                ],
                'status_breakdown' => [
                    'labels',
                    'counts',
                    'colors',
                    'total',
                    'details',
                ],
            ]);

        $json = $response->json();
        $this->assertEquals(2, $json['monthly_trend']['total_orders']);
        $this->assertEquals(2, $json['status_breakdown']['total']);
    }

    public function test_admin_product_pagination_renders_html_navigation_without_raw_entities(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(25)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('<nav role="navigation"', false);
        $response->assertDontSee('&lt;nav role=&quot;navigation&quot;', false);
    }

    /**
     * 2. Product Management Tests
     */
    public function test_admin_can_create_product_with_image_upload(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $image1 = UploadedFile::fake()->image('invitation1.jpg');
        $image2 = UploadedFile::fake()->image('invitation2.png');

        $data = [
            'category_id' => $category->id,
            'name' => 'Royal Gold Floral Luxury',
            'slug' => 'royal-gold-floral-luxury',
            'type' => 'printed',
            'short_description' => 'Desain mewah dengan foil emas',
            'description' => 'Paket lengkap kartu undangan, amplop, dan kartu ucapan.',
            'specifications' => 'Bahan Jasmine 260gr, hotprint gold',
            'price' => 15000,
            'discount_price' => 12000,
            'dp_price' => 6000,
            'min_order' => 100,
            'badge' => 'BEST SELLER',
            'stock_status' => 'ready',
            'is_featured' => 1,
            'is_active' => 1,
            'images' => [$image1, $image2],
            'primary_image_index' => 0,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Royal Gold Floral Luxury',
            'slug' => 'royal-gold-floral-luxury',
            'price' => 15000,
            'discount_price' => 12000,
            'dp_price' => 6000,
        ]);

        $product = Product::where('slug', 'royal-gold-floral-luxury')->first();
        $this->assertCount(2, $product->images);

        // Verify file stored in storage/app/public/products
        Storage::disk('public')->assertExists($product->images->first()->image_path);
        $this->assertTrue($product->images->first()->is_primary);
    }

    public function test_product_validation_rejects_invalid_discount_and_dp_prices(): void
    {
        $category = Category::factory()->create();

        // discount_price >= price
        $response1 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Produk A',
            'slug' => 'produk-a',
            'type' => 'printed',
            'description' => 'Deskripsi',
            'price' => 10000,
            'discount_price' => 12000, // Invalid: discount price is higher than price
            'dp_price' => 5000,
            'min_order' => 100,
            'stock_status' => 'ready',
        ]);
        $response1->assertSessionHasErrors('discount_price');

        // dp_price > final_price
        $response2 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Produk B',
            'slug' => 'produk-b',
            'type' => 'printed',
            'description' => 'Deskripsi',
            'price' => 10000,
            'discount_price' => 8000,
            'dp_price' => 9000, // Invalid: dp is 9000 but final price is 8000
            'min_order' => 100,
            'stock_status' => 'ready',
        ]);
        $response2->assertSessionHasErrors('dp_price');
    }

    public function test_admin_can_update_product_and_delete_single_image(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $filePath = 'products/sample.jpg';
        Storage::disk('public')->put($filePath, 'fake content');

        $image = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $filePath,
            'is_primary' => true,
            'sort_order' => 0,
        ]);

        // Delete image
        $response = $this->actingAs($this->admin)->delete(route('admin.products.images.destroy', $image));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($filePath);

        // Soft delete product
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));
        $deleteResponse->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * 3. Category Management Tests
     */
    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create(['name' => 'Gold Series']);
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_category_without_products_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * 4. Promo Management Tests
     */
    public function test_promo_crud_and_computed_status(): void
    {
        $data = [
            'code' => 'promo2026', // should be auto uppercase
            'name' => 'Diskon Awal Tahun',
            'discount_type' => 'percent',
            'discount_amount' => 15,
            'max_discount' => 100000,
            'min_purchase' => 500000,
            'usage_limit' => 50,
            'start_date' => now()->subDay()->format('Y-m-d'),
            'end_date' => now()->addDays(10)->format('Y-m-d'),
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.promos.store'), $data);
        $response->assertRedirect(route('admin.promos.index'));

        $this->assertDatabaseHas('promos', [
            'code' => 'PROMO2026',
            'discount_amount' => 15,
        ]);

        $promo = Promo::where('code', 'PROMO2026')->first();
        $this->assertEquals('active', $promo->computed_status);
        $this->assertEquals('Aktif', $promo->status_label);
    }

    /**
     * 5. Order Management & Status Update
     */
    public function test_admin_can_update_order_status_and_notes(): void
    {
        $order = Order::create([
            'order_number' => 'INV-20261010-0099',
            'user_id' => $this->member->id,
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Mawar No. 10 Jakarta',
            'shipping_method' => 'JNE Reguler',
            'payment_option' => 'dp',
            'subtotal' => 1500000,
            'discount_total' => 0,
            'total_price' => 750000,
            'status' => 'lead',
            'whatsapp_message' => 'Halo Admin...',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'contacted',
            'admin_notes' => 'Sudah dikirim preview desain via WhatsApp',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'contacted',
            'admin_notes' => 'Sudah dikirim preview desain via WhatsApp',
        ]);
    }

    public function test_admin_can_export_orders_csv(): void
    {
        Order::create([
            'order_number' => 'INV-20261010-0001',
            'user_id' => $this->member->id,
            'customer_name' => 'Siti Nurhaliza',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '081299998888',
            'shipping_address' => 'Surabaya',
            'shipping_method' => 'J&T',
            'payment_option' => 'full',
            'subtotal' => 2000000,
            'discount_total' => 100000,
            'total_price' => 1900000,
            'status' => 'confirmed',
            'whatsapp_message' => 'Halo...',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('No. Pesanan', $content);
        $this->assertStringContainsString('INV-20261010-0001', $content);
        $this->assertStringContainsString('Siti Nurhaliza', $content);
        $this->assertStringContainsString('Total Dibayar/DP', $content);
        $this->assertStringContainsString('Sisa Pelunasan', $content);
    }

    public function test_admin_order_detail_displays_item_unit_price_and_dp_breakdown(): void
    {
        $order = Order::create([
            'order_number' => 'INV-20261010-0088',
            'user_id' => $this->member->id,
            'customer_name' => 'Maya Pratama',
            'customer_email' => 'maya@example.com',
            'customer_phone' => '081234567888',
            'shipping_address' => 'Jl. Anggrek No. 12',
            'shipping_method' => 'JNE Reguler',
            'payment_option' => 'dp',
            'subtotal' => 1000000,
            'discount_total' => 0,
            'total_price' => 500000,
            'status' => 'confirmed',
            'whatsapp_message' => 'Halo...',
        ]);

        $order->items()->create([
            'product_name' => 'Kirana Botanical Single Card',
            'unit_price' => 10000,
            'dp_price' => 5000,
            'quantity' => 100,
            'subtotal' => 1000000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Harga Satuan: Rp 10.000');
        $response->assertSee('Uang Muka (DP 50%):');
        $response->assertSee('Rp 500.000');
        $response->assertSee('Sisa Tagihan Pelunasan:');
    }

    /**
     * 6. Member Management & CSV Export
     */
    public function test_admin_can_view_member_list_and_export_csv(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee($this->member->name);

        $exportResponse = $this->actingAs($this->admin)->get(route('admin.members.export'));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $exportResponse->streamedContent();
        $this->assertStringContainsString('Nama Lengkap', $content);
        $this->assertStringContainsString($this->member->name, $content);
    }

    /**
     * 7. Setting Update & WhatsApp Number Normalization
     */
    public function test_admin_update_settings_normalizes_whatsapp_number_and_updates_wa_link(): void
    {
        $data = [
            'whatsapp_number' => '0877-1234-5678', // should normalize to 6287712345678
            'site_name' => 'Galeria Premium Invitation',
            'site_tagline' => 'Undangan Pernikahan Terbaik',
            'contact_email' => 'kontak@galeria.com',
            'instagram_url' => 'https://instagram.com/galeria.wedding',
            'consultation_message' => 'Halo Admin, mau tanya...',
            'shipping_methods' => [
                ['name' => 'JNE Express', 'desc' => 'Layanan reguler'],
                ['name' => 'Gojek Instant', 'desc' => 'Jabodetabek'],
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $data);
        $response->assertRedirect(route('admin.settings.index'));

        $this->assertEquals('6287712345678', setting('whatsapp_number'));
        $this->assertEquals('Galeria Premium Invitation', setting('site_name'));

        // Verify helper wa_link uses the newly updated number
        $waLink = wa_link('Test Pesan');
        $this->assertStringContainsString('https://wa.me/6287712345678', $waLink);
    }

    /**
     * Test admin can upload and remove site logo and favicon.
     */
    public function test_admin_can_upload_and_remove_site_logo_and_favicon(): void
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('brand-logo.png', 200, 200);
        $favicon = UploadedFile::fake()->image('favicon.png', 32, 32);

        $data = [
            'whatsapp_number' => '081234567890',
            'site_name' => 'Undangin Eksklusif',
            'site_tagline' => 'Katalog Undangan Mewah',
            'contact_email' => 'halo@undangin.com',
            'consultation_message' => 'Halo Admin...',
            'site_logo' => $logo,
            'site_favicon' => $favicon,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $data);
        $response->assertRedirect(route('admin.settings.index'));

        $this->assertNotNull(setting('site_logo'));
        $this->assertNotNull(setting('site_favicon'));
        Storage::disk('public')->assertExists(setting('site_logo'));
        Storage::disk('public')->assertExists(setting('site_favicon'));

        $this->assertStringContainsString('storage/'.setting('site_logo'), site_logo_url());
        $this->assertStringContainsString('storage/'.setting('site_favicon'), site_favicon_url());

        // Now test removal of logo and favicon
        $storedLogo = setting('site_logo');
        $storedFavicon = setting('site_favicon');

        $removeData = [
            'whatsapp_number' => '081234567890',
            'site_name' => 'Undangin Eksklusif',
            'site_tagline' => 'Katalog Undangan Mewah',
            'contact_email' => 'halo@undangin.com',
            'consultation_message' => 'Halo Admin...',
            'remove_logo' => 1,
            'remove_favicon' => 1,
        ];

        $removeResponse = $this->actingAs($this->admin)->put(route('admin.settings.update'), $removeData);
        $removeResponse->assertRedirect(route('admin.settings.index'));

        $this->assertNull(setting('site_logo'));
        $this->assertNull(setting('site_favicon'));
        Storage::disk('public')->assertMissing($storedLogo);
        Storage::disk('public')->assertMissing($storedFavicon);

        $this->assertNull(site_logo_url());
        $this->assertEquals(asset('favicon.ico'), site_favicon_url());
    }

    /**
     * Test admin can create product with theme color.
     */
    public function test_admin_can_create_product_with_theme_color(): void
    {
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'name' => 'Emerald Velvet Suite',
            'slug' => 'emerald-velvet-suite',
            'type' => 'printed',
            'theme_color' => 'emerald',
            'description' => 'Deskripsi produk',
            'specifications' => 'Bahan Velvet tebal 260 gsm',
            'price' => 20000,
            'dp_price' => 10000,
            'min_order' => 50,
            'stock_status' => 'ready',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);
        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'slug' => 'emerald-velvet-suite',
            'theme_color' => 'emerald',
        ]);
    }
}
