<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_cart_or_checkout(): void
    {
        $this->get('/keranjang')->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_add_item_to_cart_via_ajax(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
            'price' => 10000,
        ]);

        $response = $this->actingAs($user)->postJson('/keranjang', [
            'product_id' => $product->id,
            'quantity' => 150,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cart_count' => 150,
            ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 150,
        ]);
    }

    public function test_adding_existing_cart_item_increments_quantity(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        $this->actingAs($user)->postJson('/keranjang', [
            'product_id' => $product->id,
            'quantity' => 50,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 150,
        ]);
    }

    public function test_adding_item_below_min_order_is_rejected(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 200,
        ]);

        $response = $this->actingAs($user)->postJson('/keranjang', [
            'product_id' => $product->id,
            'quantity' => 50,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_adding_out_of_stock_item_is_rejected(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'out_of_stock',
            'min_order' => 50,
        ]);

        $response = $this->actingAs($user)->postJson('/keranjang', [
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

    public function test_user_can_update_and_remove_cart_item(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        // Update quantity
        $this->actingAs($user)->patch("/keranjang/{$cart->id}", [
            'quantity' => 250,
        ])->assertRedirect('/keranjang');

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 250,
        ]);

        // Delete item
        $this->actingAs($user)->delete("/keranjang/{$cart->id}")
            ->assertRedirect('/keranjang');

        $this->assertDatabaseMissing('carts', [
            'id' => $cart->id,
        ]);
    }

    public function test_promo_validations(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
            'price' => 10000, // 100 pcs = 1.000.000 subtotal
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        // 1. Non-existent promo
        $this->actingAs($user)->post('/keranjang/promo', ['code' => 'TIDAKADA'])
            ->assertSessionHas('error');

        // 2. Expired promo
        $expiredPromo = Promo::factory()->create([
            'code' => 'EXPIRED10',
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->subDay(),
        ]);
        $this->actingAs($user)->post('/keranjang/promo', ['code' => $expiredPromo->code])
            ->assertSessionHas('error');

        // 3. Not started promo
        $futurePromo = Promo::factory()->create([
            'code' => 'FUTURE10',
            'start_date' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(30),
        ]);
        $this->actingAs($user)->post('/keranjang/promo', ['code' => $futurePromo->code])
            ->assertSessionHas('error');

        // 4. Usage limit exhausted
        $limitPromo = Promo::factory()->create([
            'code' => 'LIMITABIS',
            'usage_limit' => 5,
            'used_count' => 5,
        ]);
        $this->actingAs($user)->post('/keranjang/promo', ['code' => $limitPromo->code])
            ->assertSessionHas('error');

        // 5. Min purchase not met (requires 2.000.000, cart is 1.000.000)
        $minPurchasePromo = Promo::factory()->create([
            'code' => 'MINBELANJA',
            'min_purchase' => 2000000,
        ]);
        $this->actingAs($user)->post('/keranjang/promo', ['code' => $minPurchasePromo->code])
            ->assertSessionHas('error');

        // 6. Valid percentage promo with max_discount cap
        $percentPromo = Promo::factory()->create([
            'code' => 'DISKON20',
            'discount_type' => 'percent',
            'discount_amount' => 20, // 20% of 1.000.000 = 200.000, capped to 50.000
            'max_discount' => 50000,
            'min_purchase' => 500000,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post('/keranjang/promo', ['code' => $percentPromo->code])
            ->assertSessionHas('success')
            ->assertSessionHas('applied_promo_code', 'DISKON20');

        // Check cart summary reflects capped discount
        $response = $this->actingAs($user)->get('/keranjang?payment_option=full');
        $response->assertStatus(200);
        $response->assertSee('Hemat Rp 50.000');
    }

    public function test_successful_checkout_full_payment_creates_order_and_snapshots_and_clears_cart(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
        ]);
        $category = Category::factory()->create(['name' => 'Rustic Modern', 'is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Rustic Sage',
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
            'price' => 15000,
            'discount_price' => 12000,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 100, // subtotal = 100 * 12.000 = 1.200.000
        ]);

        $promo = Promo::factory()->create([
            'code' => 'HEMAT100',
            'discount_type' => 'fixed',
            'discount_amount' => 100000,
            'min_purchase' => 500000,
            'used_count' => 0,
            'is_active' => true,
        ]);

        // Simulate promo applied in session
        session(['applied_promo_code' => 'HEMAT100']);

        $checkoutData = [
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'instagram_account' => 'budisage',
            'event_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
            'shipping_address' => 'Jl. Kenanga No. 10, Jakarta Barat',
            'shipping_method' => 'JNE Reguler / YES',
            'payment_option' => 'full',
            'notes' => 'Tolong cetak draft terlebih dahulu ya',
        ];

        $response = $this->actingAs($user)->post('/checkout', $checkoutData);

        $order = Order::where('customer_email', 'budi@example.com')->first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('checkout.success', $order->order_number));

        // Subtotal = 1.200.000, Discount = 100.000, Total = 1.100.000
        $this->assertEquals(1200000, $order->subtotal);
        $this->assertEquals(100000, $order->discount_total);
        $this->assertEquals(1100000, $order->total_price);
        $this->assertEquals('full', $order->payment_option);

        // Verify order items snapshot
        $this->assertCount(1, $order->items);
        $item = $order->items->first();
        $this->assertEquals('Undangan Rustic Sage', $item->product_name);
        $this->assertEquals(12000, $item->unit_price);
        $this->assertEquals(100, $item->quantity);
        $this->assertEquals(1200000, $item->subtotal);

        // Verify promo used_count incremented
        $this->assertEquals(1, $promo->fresh()->used_count);

        // Verify cart cleared
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        // Verify WhatsApp message content
        $this->assertStringContainsString($order->order_number, $order->whatsapp_message);
        $this->assertStringContainsString('Undangan Rustic Sage', $order->whatsapp_message);
        $this->assertStringContainsString('Total Full Payment: Rp 1.100.000', $order->whatsapp_message);

        // Success page loads
        $this->actingAs($user)->get(route('checkout.success', $order->order_number))
            ->assertStatus(200)
            ->assertSee($order->order_number)
            ->assertSee('Data Pesanan Anda Sudah Tersimpan');
    }

    public function test_successful_checkout_dp_payment_calculates_total_as_dp_subtotal_regardless_of_client_input(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Undangan Royal Gold',
            'is_active' => true,
            'stock_status' => 'ready',
            'min_order' => 100,
            'price' => 20000,
            'discount_price' => null, // final = 20.000
            'dp_price' => 10000,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        // In settings, dp_percentage = 50 -> dp_price = 10.000 per unit -> total DP = 1.000.000
        $checkoutData = [
            'customer_name' => 'Siti Nurhaliza',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '085712345678',
            'instagram_account' => '@siti.wedding',
            'event_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
            'shipping_address' => 'Jl. Flamboyan No. 25, Bandung',
            'shipping_method' => 'J&T Express',
            'payment_option' => 'dp',
            'manipulated_price' => 500, // Client tries to spoof total price
        ];

        $this->actingAs($user)->post('/checkout', $checkoutData);

        $order = Order::where('customer_email', 'siti@example.com')->first();
        $this->assertNotNull($order);

        $this->assertEquals(2000000, $order->subtotal); // 100 * 20.000
        $this->assertEquals(1000000, $order->total_price); // 50% DP
        $this->assertEquals('dp', $order->payment_option);
        $this->assertStringContainsString('Total DP: Rp 1.000.000', $order->whatsapp_message);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderB = Order::factory()->create([
            'user_id' => $userB->id,
        ]);

        // User A visits User B's order -> 403 Forbidden
        $response = $this->actingAs($userA)->get("/pesanan-saya/{$orderB->id}");
        $response->assertStatus(403);
    }

    public function test_user_can_view_own_order_detail(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_number' => 'INV-20261010-9999',
        ]);

        $response = $this->actingAs($user)->get("/pesanan-saya/{$order->id}");
        $response->assertStatus(200)
            ->assertSee($order->order_number);
    }
}
