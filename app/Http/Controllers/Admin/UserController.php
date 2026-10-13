<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role', $request->routeIs('admin.members.*') ? 'member' : 'all');
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('q');

        $query = User::withCount('orders')->latest('id');

        if ($roleFilter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($roleFilter === 'member') {
            $query->where('role', 'member');
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'suspended') {
            $query->where('is_active', false);
        }

        if (! empty($search)) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $totalCount = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $memberCount = User::where('role', 'member')->count();
        $suspendedCount = User::where('is_active', false)->count();

        return view('admin.users.index', compact(
            'users',
            'roleFilter',
            'statusFilter',
            'totalCount',
            'adminCount',
            'memberCount',
            'suspendedCount'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['username'])) {
            $data['username'] = User::generateUniqueUsername($data['email'], $data['name']);
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} ({$user->role}) berhasil ditambahkan!");
    }

    /**
     * Display the specified user details and order history.
     */
    public function show(User $user): View
    {
        $orders = $user->orders()->latest()->paginate(10);

        return view('admin.users.show', compact('user', 'orders'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Safeguard: Cannot demote or suspend own currently logged-in account
        if ($user->id === auth()->id()) {
            if ($data['role'] !== 'admin' || ! $request->boolean('is_active')) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda tidak dapat menurunkan role atau menonaktifkan akun Anda sendiri yang sedang digunakan.');
            }
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    /**
     * Toggle active/suspended status for a user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan (suspended)';

        return back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->orders()->exists()) {
            return back()->with(
                'error',
                "Akun {$user->name} tidak dapat dihapus karena memiliki riwayat transaksi/pesanan tersimpan. Gunakan tombol 'Nonaktifkan Akun' untuk memblokir akses pengguna ini tanpa merusak data transaksi."
            );
        }

        $user->carts()->delete();
        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$userName} berhasil dihapus permanen.");
    }

    /**
     * Export users list to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $roleFilter = $request->query('role', 'all');
        $query = User::withCount('orders')->latest('id');

        if ($roleFilter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($roleFilter === 'member') {
            $query->where('role', 'member');
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $filename = 'daftar-pengguna-'.date('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($handle, [
                'ID',
                'Nama Lengkap',
                'Username',
                'Email',
                'Role',
                'Status Akun',
                'No. HP / WhatsApp',
                'Instagram',
                'Alamat Pengiriman',
                'Total Pesanan',
                'Tanggal Mendaftar',
            ]);

            $query->chunk(100, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->username ?? '-',
                        $user->email,
                        strtoupper($user->role),
                        $user->is_active ? 'Aktif' : 'Nonaktif (Suspended)',
                        $user->phone ?? '-',
                        $user->instagram ?? '-',
                        $user->address ?? '-',
                        $user->orders_count,
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
