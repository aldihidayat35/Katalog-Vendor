<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    /**
     * Display a listing of banners.
     */
    public function index(Request $request): View
    {
        $query = Banner::orderBy('sort_order')->latest('id');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        $banners = $query->paginate(10)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create(): View
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(BannerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = ImageService::storeAsWebp($request->file('image'), 'banners', 1600);
        }

        unset($data['image']);

        Banner::create($data);

        Cache::forget('homepage_data');

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner promosi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = ImageService::storeAsWebp($request->file('image'), 'banners', 1600);
        }

        unset($data['image']);

        $banner->update($data);

        Cache::forget('homepage_data');

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner promosi berhasil diperbarui.');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        Cache::forget('homepage_data');

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner promosi berhasil dihapus.');
    }
}
