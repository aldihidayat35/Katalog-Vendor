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
     * Compute summary (subtotal, DP subtotal, promo discount, total).
     */
    public function getSummary(User $user, string $paymentOption = 'full', ?string $promoCode = null): array
    {
        $items = $this->getItems($user);

        $subtotal = 0;
        $dpSubtotal = 0;
        $totalItems = 0;
        $dpPercentage = (float) setting('dp_percentage', 50);

        foreach ($items as $item) {
            $unitPrice = (int) $item->product->getUnitPriceForQuantity($item->quantity);
            $rawDpPrice = (int) $item->product->getDpPriceForQuantity($item->quantity);

            // Per-unit DP: If product has custom dp_price <= unitPrice, use it.
            // Otherwise, calculate standard 50% (dp_percentage) of unit price.
            $unitDp = ($rawDpPrice > 0 && $rawDpPrice <= $unitPrice)
                ? $rawDpPrice
                : (int) round(($unitPrice * $dpPercentage) / 100);

            $item->unit_price = $unitPrice;
            $item->is_wholesale = $item->product->isWholesaleForQuantity($item->quantity);
            $item->active_tier = $item->product->getTierForQuantity($item->quantity);
            $item->item_subtotal = $unitPrice * $item->quantity;
            $item->subtotal = $unitPrice * $item->quantity;
            $item->unit_dp = $unitDp;
            $item->dp_subtotal = $unitDp * $item->quantity;

            $subtotal += ($unitPrice * $item->quantity);
            $dpSubtotal += ($unitDp * $item->quantity);
            $totalItems += $item->quantity;
        }

        // Promo calculation (only applies to Full Payment)
        $promoResult = null;
        $discount = 0;

        if (! empty($promoCode) && $subtotal > 0) {
            $promoResult = $this->promoService->validatePromo($promoCode, $subtotal);
            if ($promoResult['valid'] && $paymentOption === 'full') {
                $discount = $promoResult['discount'];
            }
        }

        $totalPrice = match ($paymentOption) {
            'dp' => $dpSubtotal,
            default => max(0, $subtotal - $discount),
        };

        $remainingBalance = match ($paymentOption) {
            'dp' => max(0, $subtotal - $dpSubtotal),
            default => 0,
        };

        return [
            'items' => $items,
            'item_count' => $items->count(),
            'total_quantity' => $totalItems,
            'subtotal' => $subtotal,
            'dp_subtotal' => $dpSubtotal,
            'remaining_balance' => $remainingBalance,
            'discount' => $discount,
            'payment_option' => $paymentOption,
            'total' => $totalPrice,
            'promo' => $promoResult,
        ];
    }
}
