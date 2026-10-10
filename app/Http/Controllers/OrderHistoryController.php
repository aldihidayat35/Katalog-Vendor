<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of orders for the authenticated user.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest('id')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['items.product.category', 'promo']);

        $waLink = wa_link("Halo Admin, saya ingin menanyakan perkembangan pesanan aku dengan nomor {$order->order_number}");

        return view('order.show', compact('order', 'waLink'));
    }
}
