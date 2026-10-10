<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {}

    /**
     * Display checkout form and sticky order summary.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();
        $items = $this->cartService->getItems($user);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $paymentOption = session('checkout_payment_option', 'full');
        $promoCode = session('applied_promo_code');
        $summary = $this->cartService->getSummary($user, $paymentOption, $promoCode);

        $rawShipping = setting('shipping_methods');
        $shippingMethods = is_string($rawShipping) ? json_decode($rawShipping, true) : (is_array($rawShipping) ? $rawShipping : []);

        if (empty($shippingMethods)) {
            $shippingMethods = [
                ['id' => 'jne', 'name' => 'JNE Reguler / YES', 'desc' => 'Pengiriman ekspedisi ke seluruh pelosok Indonesia.'],
                ['id' => 'jnt', 'name' => 'J&T Express', 'desc' => 'Layanan cepat pick-up dan tracking pengiriman akurat.'],
                ['id' => 'instant', 'name' => 'Gojek / Grab (Instan)', 'desc' => 'Khusus wilayah Jabodetabek saat pesanan selesai.'],
                ['id' => 'pickup', 'name' => 'Ambil di Tempat (Self-Pickup)', 'desc' => 'Ambil langsung di workshop Galeria Invitation.'],
            ];
        }

        return view('checkout.index', compact('user', 'summary', 'shippingMethods', 'paymentOption'));
    }

    /**
     * Process checkout and create order.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->checkoutService->process(auth()->user(), $request->validated());

        return redirect()->route('checkout.success', $order->order_number)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    /**
     * Display checkout success page with WhatsApp redirect.
     */
    public function success(string $order_number): View
    {
        $order = Order::with(['items.product.category'])
            ->where('order_number', $order_number)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $waLink = wa_link($order->whatsapp_message);

        return view('checkout.success', compact('order', 'waLink'));
    }
}
