<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $now = Carbon::now();

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'total_members' => User::where('role', 'member')->count(),
            'new_leads' => Order::where('status', 'lead')->count(),
            'monthly_orders' => Order::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count(),
            'monthly_revenue' => (int) Order::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('total_price'),
        ];

        $recentLeads = Order::where('status', 'lead')
            ->latest()
            ->take(5)
            ->get();

        // Fallback to recent orders if less than 5 leads
        if ($recentLeads->isEmpty()) {
            $recentLeads = Order::latest()->take(5)->get();
        }

        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'recentProducts'));
    }
}
