<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use App\Services\PromoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected PromoService $promoService
    ) {}

    /**
     * Display the shopping cart.
     */
    public function index(Request $request): View
    {
        $paymentOption = $request->query('payment_option', session('checkout_payment_option', 'full'));
        if (! in_array($paymentOption, ['dp', 'full'])) {
            $paymentOption = 'full';
        }

        session(['checkout_payment_option' => $paymentOption]);

        $promoCode = session('applied_promo_code');
        $summary = $this->cartService->getSummary(auth()->user(), $paymentOption, $promoCode);

        return view('cart.index', compact('summary', 'paymentOption'));
    }

    /**
     * Add product to cart.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cartService->add(
            auth()->user(),
            (int) $validated['product_id'],
            (int) $validated['quantity']
        );

        $cartCount = (int) auth()->user()->carts()->sum('quantity');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity of an item in the cart.
     */
    public function update(Request $request, Cart $cart): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cartService->update(auth()->user(), $cart, (int) $validated['quantity']);

        if ($request->expectsJson() || $request->ajax()) {
            $paymentOption = session('checkout_payment_option', 'full');
            $summary = $this->cartService->getSummary(auth()->user(), $paymentOption, session('applied_promo_code'));
            $cartCount = (int) auth()->user()->carts()->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Jumlah pesanan berhasil diperbarui!',
                'cart_count' => $cartCount,
                'summary' => $summary,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah pesanan berhasil diperbarui!');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request, Cart $cart): JsonResponse|RedirectResponse
    {
        $this->cartService->remove(auth()->user(), $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart_count' => (int) auth()->user()->carts()->sum('quantity'),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Apply promo code to cart.
     */
    public function applyPromo(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $summary = $this->cartService->getSummary(auth()->user());

        if ($summary['subtotal'] <= 0) {
            return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $result = $this->promoService->validatePromo($request->code, $summary['subtotal']);

        if ($result['valid']) {
            session(['applied_promo_code' => $result['promo']->code]);
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Remove applied promo code from session.
     */
    public function removePromo(): RedirectResponse
    {
        session()->forget('applied_promo_code');
        return redirect()->back()->with('info', 'Kode promo berhasil dihapus dari keranjang.');
    }
}
