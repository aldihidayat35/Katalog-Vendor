<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WholesalePricingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@galeria.com',
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->category = Category::factory()->create([
            'name' => 'Undangan Rustic',
            'slug' => 'undangan-rustic',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_product_with_price_tiers(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Undangan Custom Rustic',
            'slug' => 'undangan-custom-rustic',
            'type' => 'printed',
            'price' => 1200,
            'discount_price' => null,
            'min_order' => 50,
            'stock_status' => 'ready',
            'description' => 'Deskripsi paket lengkap undangan rustic',
            'specifications' => 'Bahan kertas jasmine 210gsm',
            'tiers' => [
                [
                    'min_quantity' => 50,
                    'max_quantity' => 100,
                    'unit_price' => 1000,
                ],
                [
                    'min_quantity' => 101,
                    'max_quantity' => 200,
                    'unit_price' => 800,
                ],
                [
                    'min_quantity' => 201,
                    'max_quantity' => null,
                    'unit_price' => 700,
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'undangan-custom-rustic')->firstOrFail();
        $this->assertCount(3, $product->priceTiers);

        $tier1 = $product->priceTiers()->where('min_quantity', 50)->first();
        $this->assertEquals(100, $tier1->max_quantity);
        $this->assertEquals(1000, $tier1->unit_price);

        $tier3 = $product->priceTiers()->where('min_quantity', 201)->first();
        $this->assertNull($tier3->max_quantity);
        $this->assertEquals(700, $tier3->unit_price);
    }

    public function test_price_tier_quantity_matching_and_fallback(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 1200,
            'discount_price' => null,
            'min_order' => 50,
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 50,
            'max_quantity' => 100,
            'unit_price' => 1000,
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 101,
            'max_quantity' => 200,
            'unit_price' => 800,
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 201,
            'max_quantity' => 300,
            'unit_price' => 700,
        ]);

        $product->refresh();

        // 75 pcs should match tier 1 (1000)
        $this->assertEquals(1000, $product->getUnitPriceForQuantity(75));
        $this->assertTrue($product->isWholesaleForQuantity(75));

        // 150 pcs should match tier 2 (800)
        $this->assertEquals(800, $product->getUnitPriceForQuantity(150));
        $this->assertTrue($product->isWholesaleForQuantity(150));

        // 500 pcs exceeds 300 but should fall back to best tier (700)
        $this->assertEquals(700, $product->getUnitPriceForQuantity(500));
    }

    public function test_cart_service_calculates_wholesale_pricing(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 1200,
            'min_order' => 10,
            'is_active' => true,
            'stock_status' => 'ready',
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 1,
            'max_quantity' => 100,
            'unit_price' => 1000,
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 101,
            'max_quantity' => 200,
            'unit_price' => 800,
        ]);

        /** @var CartService $cartService */
        $cartService = app(CartService::class);

        // Add 150 pcs (tier 2: 800/pcs)
        $cartService->add($this->customer, $product->id, 150);

        $summary = $cartService->getSummary($this->customer);
        $item = $summary['items']->first();

        $this->assertEquals(800, $item->unit_price);
        $this->assertTrue($item->is_wholesale);
        $this->assertEquals(120000, $item->subtotal); // 150 * 800
        $this->assertEquals(120000, $summary['total']);
    }

    public function test_checkout_saves_wholesale_pricing_and_generates_whatsapp_with_wholesale_tags(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Undangan Pernikahan Mewah',
            'price' => 1200,
            'min_order' => 10,
            'is_active' => true,
            'stock_status' => 'ready',
        ]);

        ProductPriceTier::create([
            'product_id' => $product->id,
            'min_quantity' => 100,
            'max_quantity' => 200,
            'unit_price' => 800,
        ]);

        /** @var CartService $cartService */
        $cartService = app(CartService::class);
        $cartService->add($this->customer, $product->id, 150);

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        $order = $checkoutService->process($this->customer, [
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta',
            'shipping_method' => 'JNE Reguler',
            'event_date' => now()->addDays(30)->toDateString(),
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(120000, $order->subtotal);
        $this->assertEquals(120000, $order->total_price);

        $item = $order->items->first();
        $this->assertEquals(800, $item->unit_price);
        $this->assertTrue($item->isWholesale());

        // WhatsApp message must contain wholesale indicators
        $this->assertStringContainsString('[Grosir]', $order->whatsapp_message);
        $this->assertStringContainsString('(Harga Grosir)', $order->whatsapp_message);
        $this->assertStringContainsString('Total Pesanan: Rp 120.000', $order->whatsapp_message);
    }
}
