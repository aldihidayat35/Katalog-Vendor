<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndPerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that User A cannot delete or manipulate User B's cart item (IDOR Prevention).
     */
    public function test_user_cannot_manipulate_other_users_cart_item(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 50,
        ]);

        $cartItemB = Cart::create([
            'user_id' => $userB->id,
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        // User A attempts to update User B's cart item -> 403 Forbidden
        $updateResponse = $this->actingAs($userA)->patch("/keranjang/{$cartItemB->id}", [
            'quantity' => 200,
        ]);
        $updateResponse->assertStatus(403);

        // User A attempts to delete User B's cart item -> 403 Forbidden
        $deleteResponse = $this->actingAs($userA)->delete("/keranjang/{$cartItemB->id}");
        $deleteResponse->assertStatus(403);

        // Verify cart item B was NOT altered in database
        $this->assertDatabaseHas('carts', [
            'id' => $cartItemB->id,
            'user_id' => $userB->id,
            'quantity' => 100,
        ]);
    }

    /**
     * Test sitemap.xml endpoint returns valid XML response with active catalog URLs.
     */
    public function test_sitemap_xml_renders_valid_content(): void
    {
        $category = Category::factory()->create(['is_active' => true, 'slug' => 'diamond-exclusive']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'slug' => 'undangan-diamond-royal',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', $response->headers->get('Content-Type') ?? '');
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString(route('catalog.index', ['kategori' => $category->slug]), $response->getContent());
        $this->assertStringContainsString(route('product.show', $product->slug), $response->getContent());
    }

    /**
     * Test robots.txt file exists and protects private administrative paths.
     */
    public function test_robots_txt_protects_sensitive_routes(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /checkout', $content);
        $this->assertStringContainsString('Disallow: /keranjang', $content);
        $this->assertStringContainsString('Sitemap:', $content);
    }

    /**
     * Test custom 404 page is rendered for non-existent routes.
     */
    public function test_custom_404_page_rendered(): void
    {
        $response = $this->get('/halaman-yang-pasti-tidak-ada-123456');

        $response->assertStatus(404);
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('Kembali ke Beranda');
    }

    /**
     * Test regular authenticated member cannot access admin panel.
     */
    public function test_regular_member_forbidden_from_admin_area(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get('/admin/dashboard');

        // AdminMiddleware redirects unauthorized user with flash error or 403
        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
    }

    /**
     * Test ProductionSeeder seeds only admin, settings, and categories without demo products.
     */
    public function test_production_seeder_executes_cleanly(): void
    {
        $this->seed(ProductionSeeder::class);

        // Verify Admin user created
        $this->assertDatabaseHas('users', [
            'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@katalogundangan.com'),
            'role' => 'admin',
        ]);

        // Verify Categories seeded
        $this->assertDatabaseHas('categories', ['slug' => 'bronze']);
        $this->assertDatabaseHas('categories', ['slug' => 'diamond']);

        // Verify Settings seeded
        $this->assertDatabaseHas('settings', ['key' => 'whatsapp_number']);

        // Verify NO demo products exist from production seeder
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseCount('orders', 0);
    }
}
