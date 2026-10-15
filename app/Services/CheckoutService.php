<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        protected PromoService $promoService,
        protected WhatsAppMessageBuilder $waBuilder
    ) {}

    /**
     * Process checkout in a single database transaction.
     */
    public function process(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. Fetch and validate cart items with lock
            $cartItems = Cart::with(['product' => fn ($q) => $q->with(['category', 'priceTiers'])])
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.',
                ]);
            }

            foreach ($cartItems as $item) {
                $product = $item->product;

                if (! $product || ! $product->is_active || ! $product->category?->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => "Produk '{$product?->name}' sedang tidak aktif atau tidak tersedia.",
                    ]);
                }

                if ($product->stock_status === 'out_of_stock') {
                    throw ValidationException::withMessages([
                        'cart' => "Produk '{$product->name}' sedang habis stok dan tidak dapat diproses.",
                    ]);
                }

                if ($item->quantity < $product->min_order) {
                    throw ValidationException::withMessages([
                        'cart' => "Jumlah pemesanan untuk '{$product->name}' minimal {$product->min_order} pcs.",
                    ]);
                }
            }

            // 2. Server-side price & promo recalculation
            $subtotal = 0;
            $itemCalculations = [];

            foreach ($cartItems as $item) {
                $unitPrice = (int) $item->product->getUnitPriceForQuantity($item->quantity);

                $itemCalculations[$item->id] = [
                    'unit_price' => $unitPrice,
                    'subtotal' => (int) ($unitPrice * $item->quantity),
                ];

                $subtotal += ($unitPrice * $item->quantity);
            }

            $promoCode = session('applied_promo_code');
            $promoId = null;
            $appliedPromoCode = null;
            $discountTotal = 0;

            if (! empty($promoCode)) {
                $promoResult = $this->promoService->validatePromo($promoCode, $subtotal);

                if (! $promoResult['valid']) {
                    session()->forget('applied_promo_code');
                    throw ValidationException::withMessages([
                        'promo_code' => $promoResult['message'],
                    ]);
                }

                $discountTotal = (int) $promoResult['discount'];
                $promoId = $promoResult['promo']?->id;
                $appliedPromoCode = $promoResult['promo']?->code;
            }

            $totalPrice = max(0, $subtotal - $discountTotal);

            // 3. Generate unique sequential daily order number (e.g. INV-20261010-0001)
            $today = date('Ymd');
            $prefix = "INV-{$today}-";

            $lastOrder = Order::where('order_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextSeq = 1;
            if ($lastOrder && preg_match('/^INV-\d{8}-(\d{4})$/', $lastOrder->order_number, $matches)) {
                $nextSeq = (int) $matches[1] + 1;
            }

            $orderNumber = sprintf('%s%04d', $prefix, $nextSeq);

            // 4. Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => clean_phone_number($data['customer_phone']),
                'instagram_account' => ! empty($data['instagram_account']) ? ltrim($data['instagram_account'], '@') : null,
                'event_date' => $data['event_date'],
                'shipping_address' => $data['shipping_address'],
                'shipping_method' => $data['shipping_method'],
                'promo_id' => $promoId,
                'promo_code' => $appliedPromoCode,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'total_price' => $totalPrice,
                'notes' => $data['notes'] ?? null,
                'status' => 'lead',
                'whatsapp_message' => '',
            ]);

            // 5. Create Order Items Snapshot
            foreach ($cartItems as $item) {
                $calc = $itemCalculations[$item->id] ?? [
                    'unit_price' => (int) $item->product->final_price,
                    'subtotal' => (int) ($item->product->final_price * $item->quantity),
                ];

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'unit_price' => $calc['unit_price'],
                    'quantity' => $item->quantity,
                    'subtotal' => $calc['subtotal'],
                ]);
            }

            // 6. Build WhatsApp message text & save to orders.whatsapp_message
            $order->load(['items.product.category', 'items.product.priceTiers']);
            $whatsappMessage = $this->waBuilder->build($order);
            $order->update(['whatsapp_message' => $whatsappMessage]);

            // 7. Increment promo usage
            if ($promoId) {
                Promo::where('id', $promoId)->increment('used_count');
            }

            // 8. Empty user cart and clear session promo
            Cart::where('user_id', $user->id)->delete();
            session()->forget('applied_promo_code');

            // 9. Update user profile default info if missing
            $userUpdates = [];
            if (empty($user->phone)) {
                $userUpdates['phone'] = clean_phone_number($data['customer_phone']);
            }
            if (empty($user->instagram) && ! empty($data['instagram_account'])) {
                $userUpdates['instagram'] = ltrim($data['instagram_account'], '@');
            }
            if (empty($user->address)) {
                $userUpdates['address'] = $data['shipping_address'];
            }
            if (! empty($userUpdates)) {
                $user->update($userUpdates);
            }

            return $order;
        });
    }
}
