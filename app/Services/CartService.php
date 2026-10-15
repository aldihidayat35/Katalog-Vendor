<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(
        protected PromoService $promoService
    ) {}

    /**
     * Add product to user cart.
     */
    public function add(User $user, int $productId, int $quantity): Cart
    {
        $product = Product::active()
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->find($productId);

        if (! $product) {
            throw ValidationException::withMessages([
                'product_id' => 'Produk tidak ditemukan atau sedang tidak aktif.',
            ]);
        }

        if ($product->stock_status === 'out_of_stock') {
            throw ValidationException::withMessages([
                'product_id' => 'Stok produk sedang habis dan tidak dapat dipesan.',
            ]);
        }

        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah pesanan harus minimal 1.',
            ]);
        }

        $existingItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity < $product->min_order) {
                throw ValidationException::withMessages([
                    'quantity' => "Jumlah pemesanan minimal untuk {$product->name} adalah {$product->min_order} pcs.",
                ]);
            }

            $existingItem->update(['quantity' => $newQuantity]);

            return $existingItem->load('product');
        }

        if ($quantity < $product->min_order) {
            throw ValidationException::withMessages([
                'quantity' => "Jumlah pemesanan minimal untuk {$product->name} adalah {$product->min_order} pcs.",
            ]);
        }

        return Cart::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'quantity' => $quantity,
        ])->load('product');
    }

    /**
     * Update cart item quantity.
     */
    public function update(User $user, Cart $cart, int $quantity): Cart
    {
        if ($cart->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke item keranjang ini.');
        }

        $product = $cart->product;

        if (! $product || ! $product->is_active) {
            throw ValidationException::withMessages([
                'quantity' => 'Produk ini sedang tidak aktif dan tidak dapat diperbarui.',
            ]);
        }

        if ($product->stock_status === 'out_of_stock') {
            throw ValidationException::withMessages([
                'quantity' => 'Stok produk ini sedang habis.',
            ]);
        }

        if ($quantity < $product->min_order) {
            throw ValidationException::withMessages([
                'quantity' => "Jumlah pemesanan minimal adalah {$product->min_order} pcs.",
            ]);
        }

        $cart->update(['quantity' => $quantity]);

        return $cart->fresh(['product.category', 'product.images']);
    }

    /**
     * Remove item from cart.
     */
    public function remove(User $user, Cart $cart): bool
    {
        if ($cart->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke item keranjang ini.');
        }

        return (bool) $cart->delete();
    }

    /**
     * Get all cart items for user.
     *
     * @return Collection<int, Cart>
     */
    public function getItems(User $user): Collection
    {
        return Cart::with([
            'product' => fn ($q) => $q->with(['category', 'priceTiers', 'images' => fn ($img) => $img->orderBy('sort_order')]),
        ])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();
    }

    /**
     * Compute summary (subtotal, promo discount, total).
     */
    public function getSummary(User $user, ?string $promoOrPayment = null, ?string $promoCode = null): array
    {
        // Support both getSummary($user, $promoCode) and legacy getSummary($user, $paymentOption, $promoCode)
        $actualPromo = $promoCode;
        if ($actualPromo === null && ! in_array($promoOrPayment, ['full', 'dp'], true)) {
            $actualPromo = $promoOrPayment;
        }

        $items = $this->getItems($user);

        $subtotal = 0;
        $totalItems = 0;

        foreach ($items as $item) {
            $unitPrice = (int) $item->product->getUnitPriceForQuantity($item->quantity);

            $item->unit_price = $unitPrice;
            $item->is_wholesale = $item->product->isWholesaleForQuantity($item->quantity);
            $item->active_tier = $item->product->getTierForQuantity($item->quantity);
            $item->item_subtotal = $unitPrice * $item->quantity;
            $item->subtotal = $unitPrice * $item->quantity;

            $subtotal += ($unitPrice * $item->quantity);
            $totalItems += $item->quantity;
        }

        // Promo calculation applies directly to all orders
        $promoResult = null;
        $discount = 0;

        if (! empty($actualPromo) && $subtotal > 0) {
            $promoResult = $this->promoService->validatePromo($actualPromo, $subtotal);
            if ($promoResult['valid']) {
                $discount = (int) $promoResult['discount'];
            }
        }

        $totalPrice = max(0, $subtotal - $discount);

        return [
            'items' => $items,
            'item_count' => $items->count(),
            'total_quantity' => $totalItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $totalPrice,
            'promo' => $promoResult,
        ];
    }
}
