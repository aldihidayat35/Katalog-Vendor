<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
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

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearSql = $isSqlite ? "cast(strftime('%Y', created_at) as integer)" : 'YEAR(created_at)';

        // Available years from orders
        $availableYears = Order::selectRaw("{$yearSql} as year")
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->toArray();

        $currentYear = (int) $now->year;
        if (! in_array($currentYear, $availableYears, true)) {
            array_unshift($availableYears, $currentYear);
        }

        $selectedYear = (int) $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', 'all');

        $initialAnalytics = $this->formatAnalyticsData($selectedYear, $selectedMonth);

        $monthsList = [
            'all' => 'Semua Bulan (Setahun Penuh)',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentLeads',
            'recentProducts',
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'monthsList',
            'initialAnalytics'
        ));
    }

    /**
     * Return analytics JSON data for dashboard charts.
     */
    public function analytics(Request $request): JsonResponse
    {
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = $request->input('month', 'all');

        $data = $this->formatAnalyticsData($year, $month);

        return response()->json($data);
    }

    /**
     * Compute analytics data for monthly trends and status distribution.
     *
     * @return array<string, mixed>
     */
    protected function formatAnalyticsData(int $year, string|int $month = 'all'): array
    {
        $monthLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthSql = $isSqlite ? "cast(strftime('%m', created_at) as integer)" : 'MONTH(created_at)';

        // 1. Monthly orders count and revenue for the whole selected year
        $monthlyOrders = Order::selectRaw("{$monthSql} as month, COUNT(*) as count")
            ->whereYear('created_at', $year)
            ->groupByRaw($monthSql)
            ->pluck('count', 'month');

        $monthlyRevenue = Order::selectRaw("{$monthSql} as month, SUM(total_price) as revenue")
            ->whereYear('created_at', $year)
            ->groupByRaw($monthSql)
            ->pluck('revenue', 'month');

        $trendLabels = [];
        $trendOrders = [];
        $trendRevenues = [];
        $totalOrdersInYear = 0;
        $totalRevenueInYear = 0;

        foreach ($monthLabels as $mNum => $mLabel) {
            $count = (int) ($monthlyOrders[$mNum] ?? 0);
            $rev = (int) ($monthlyRevenue[$mNum] ?? 0);

            $trendLabels[] = $mLabel;
            $trendOrders[] = $count;
            $trendRevenues[] = $rev;

            $totalOrdersInYear += $count;
            $totalRevenueInYear += $rev;
        }

        // 2. Status distribution query (filtered by year, and optionally by specific month)
        $statusQuery = Order::query()->whereYear('created_at', $year);

        if ($month !== 'all' && is_numeric($month) && (int) $month >= 1 && (int) $month <= 12) {
            $statusQuery->whereMonth('created_at', (int) $month);
        }

        $statusCounts = $statusQuery->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $statusDefinitions = [
            'lead' => [
                'label' => 'Lead Baru',
                'color' => '#F59E0B',
            ],
            'contacted' => [
                'label' => 'Sudah Dihubungi',
                'color' => '#3B82F6',
            ],
            'confirmed' => [
                'label' => 'DP Diterima',
                'color' => '#10B981',
            ],
            'completed' => [
                'label' => 'Lunas / Selesai',
                'color' => '#8B5CF6',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'color' => '#EF4444',
            ],
        ];

        $pieLabels = [];
        $pieCounts = [];
        $pieColors = [];
        $pieDetails = [];
        $totalOrdersInPie = 0;

        foreach ($statusDefinitions as $statusKey => $def) {
            $cnt = (int) ($statusCounts[$statusKey] ?? 0);
            $totalOrdersInPie += $cnt;

            $pieLabels[] = $def['label'];
            $pieCounts[] = $cnt;
            $pieColors[] = $def['color'];
        }

        foreach ($statusDefinitions as $statusKey => $def) {
            $cnt = (int) ($statusCounts[$statusKey] ?? 0);
            $pct = $totalOrdersInPie > 0 ? round(($cnt / $totalOrdersInPie) * 100, 1) : 0;

            $pieDetails[] = [
                'key' => $statusKey,
                'label' => $def['label'],
                'count' => $cnt,
                'percentage' => $pct,
                'color' => $def['color'],
            ];
        }

        return [
            'year' => $year,
            'month' => $month,
            'monthly_trend' => [
                'labels' => $trendLabels,
                'orders' => $trendOrders,
                'revenue' => $trendRevenues,
                'total_orders' => $totalOrdersInYear,
                'total_revenue' => $totalRevenueInYear,
            ],
            'status_breakdown' => [
                'labels' => $pieLabels,
                'counts' => $pieCounts,
                'colors' => $pieColors,
                'total' => $totalOrdersInPie,
                'details' => $pieDetails,
            ],
        ];
    }
}
