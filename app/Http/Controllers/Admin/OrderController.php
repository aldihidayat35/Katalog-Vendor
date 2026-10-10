<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of orders/leads with filters and search.
     */
    public function index(Request $request): View
    {
        $query = $this->buildFilteredQuery($request);
        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'user', 'promo']);

        $cleanPhone = clean_phone_number($order->customer_phone);
        $siteName = setting('site_name', 'Galeria Invitation');
        $waGreeting = "Halo Kak {$order->customer_name}, terima kasih telah memesan di {$siteName}. Menindaklanjuti pesanan Anda dengan nomor {$order->order_number}, apakah ada detail desain yang ingin didiskusikan?";
        $waCustomerUrl = "https://wa.me/{$cleanPhone}?text=" . urlencode($waGreeting);

        return view('admin.orders.show', compact('order', 'waCustomerUrl'));
    }

    /**
     * Update order status and internal notes.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:lead,contacted,confirmed,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        $order->update([
            'status' => $request->input('status'),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Status pesanan & catatan berhasil diperbarui.');
    }

    /**
     * Export filtered orders list to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = $this->buildFilteredQuery($request);
        $filename = 'pesanan-leads-' . date('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($handle, [
                'No. Pesanan',
                'Nama Customer',
                'Email',
                'No. HP',
                'Instagram',
                'Tanggal Acara',
                'Metode Pengiriman',
                'Opsi Pembayaran',
                'Kode Promo',
                'Subtotal',
                'Diskon',
                'Total',
                'Status',
                'Catatan Customer',
                'Catatan Admin',
                'Tanggal Pesan',
            ]);

            $query->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->customer_name,
                        $order->customer_email,
                        $order->customer_phone,
                        $order->instagram_account ?? '-',
                        $order->event_date ? $order->event_date->format('d/m/Y') : '-',
                        $order->shipping_method,
                        strtoupper($order->payment_option),
                        $order->promo_code ?? '-',
                        $order->subtotal,
                        $order->discount_total,
                        $order->total_price,
                        $order->status,
                        $order->notes ?? '-',
                        $order->admin_notes ?? '-',
                        $order->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Helper to build filtered query for orders.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = Order::latest('id');

        // Search
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_email', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        return $query;
    }
}
