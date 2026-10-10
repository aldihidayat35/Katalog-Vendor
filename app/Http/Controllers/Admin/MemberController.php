<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller
{
    /**
     * Display a listing of registered members.
     */
    public function index(Request $request): View
    {
        $query = $this->buildFilteredQuery($request);
        $members = $query->paginate(15)->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    /**
     * Display member profile details and order history.
     */
    public function show(User $user): View
    {
        if ($user->role !== 'member') {
            abort(404);
        }

        $orders = $user->orders()->latest()->paginate(10);

        return view('admin.members.show', compact('user', 'orders'));
    }

    /**
     * Export members list to CSV for marketing purposes.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = $this->buildFilteredQuery($request);
        $filename = 'daftar-member-' . date('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($handle, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($handle, [
                'Nama Lengkap',
                'Email',
                'No. HP / WhatsApp',
                'Akun Instagram',
                'Alamat Pengiriman Default',
                'Jumlah Pesanan',
                'Tanggal Mendaftar',
            ]);

            $query->chunk(100, function ($members) use ($handle) {
                foreach ($members as $member) {
                    fputcsv($handle, [
                        $member->name,
                        $member->email,
                        $member->phone ?? '-',
                        $member->instagram ?? '-',
                        $member->address ?? '-',
                        $member->orders_count,
                        $member->created_at->format('Y-m-d H:i:s'),
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
     * Helper to build filtered member query.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = User::where('role', 'member')
            ->withCount('orders')
            ->latest('id');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return $query;
    }
}
