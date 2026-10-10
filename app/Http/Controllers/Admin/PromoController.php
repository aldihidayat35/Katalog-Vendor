<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromoRequest;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoController extends Controller
{
    /**
     * Display a listing of promos.
     */
    public function index(Request $request): View
    {
        $query = Promo::latest('id');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('code', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $now = now();
            if ($status === 'active') {
                $query->where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                            ->orWhereColumn('used_count', '<', 'usage_limit');
                    });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('is_active', true)->where('end_date', '<', $now);
            }
        }

        $promos = $query->paginate(10)->withQueryString();

        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new promo.
     */
    public function create(): View
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created promo in storage.
     */
    public function store(PromoRequest $request): RedirectResponse
    {
        Promo::create($request->validated());

        return redirect()->route('admin.promos.index')
            ->with('success', 'Kupon promo berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified promo.
     */
    public function edit(Promo $promo): View
    {
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified promo in storage.
     */
    public function update(PromoRequest $request, Promo $promo): RedirectResponse
    {
        $promo->update($request->validated());

        return redirect()->route('admin.promos.index')
            ->with('success', 'Kupon promo berhasil diperbarui.');
    }

    /**
     * Remove the specified promo from storage.
     */
    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Kupon promo berhasil dihapus.');
    }
}
