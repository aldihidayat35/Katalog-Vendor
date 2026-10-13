<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with search and filters.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with(['category', 'images'])
            ->latest('id');

        // Search by name
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->input('q').'%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->input('stock_status'));
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::active()->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('name')->get();
        }

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            unset($data['images'], $data['primary_image_index'], $data['tiers']);

            $product = Product::create($data);

            // Handle wholesale price tiers
            if ($request->has('tiers') && is_array($request->input('tiers'))) {
                foreach ($request->input('tiers') as $tierData) {
                    if (isset($tierData['min_quantity']) && $tierData['min_quantity'] !== '' && isset($tierData['unit_price']) && $tierData['unit_price'] !== '') {
                        $product->priceTiers()->create([
                            'min_quantity' => (int) $tierData['min_quantity'],
                            'max_quantity' => isset($tierData['max_quantity']) && $tierData['max_quantity'] !== '' ? (int) $tierData['max_quantity'] : null,
                            'unit_price' => (int) $tierData['unit_price'],
                        ]);
                    }
                }
            }

            // Handle multi image uploads
            if ($request->hasFile('images')) {
                $primaryIndex = (int) $request->input('primary_image_index', 0);

                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => ($index === $primaryIndex),
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        Cache::forget('homepage_data');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load(['category', 'images', 'priceTiers']);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $data = $request->validated();
            unset($data['images'], $data['primary_image_index'], $data['tiers']);

            $product->update($data);

            // Sync wholesale price tiers
            $product->priceTiers()->delete();
            if ($request->has('tiers') && is_array($request->input('tiers'))) {
                foreach ($request->input('tiers') as $tierData) {
                    if (isset($tierData['min_quantity']) && $tierData['min_quantity'] !== '' && isset($tierData['unit_price']) && $tierData['unit_price'] !== '') {
                        $product->priceTiers()->create([
                            'min_quantity' => (int) $tierData['min_quantity'],
                            'max_quantity' => isset($tierData['max_quantity']) && $tierData['max_quantity'] !== '' ? (int) $tierData['max_quantity'] : null,
                            'unit_price' => (int) $tierData['unit_price'],
                        ]);
                    }
                }
            }

            // Handle primary image update from existing images
            if ($request->filled('primary_image_id')) {
                $product->images()->update(['is_primary' => false]);
                $product->images()->where('id', $request->input('primary_image_id'))->update(['is_primary' => true]);
            }

            // Handle additional image uploads
            if ($request->hasFile('images')) {
                $maxSort = (int) $product->images()->max('sort_order');
                $hasPrimary = $product->images()->where('is_primary', true)->exists();

                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $isPrimary = (! $hasPrimary && $i === 0);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $isPrimary,
                        'sort_order' => $maxSort + 1 + $i,
                    ]);
                }
            }
        });

        Cache::forget('homepage_data');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        Cache::forget('homepage_data');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus (soft delete).');
    }

    /**
     * Delete an individual product image.
     */
    public function destroyImage(ProductImage $productImage): RedirectResponse
    {
        $productId = $productImage->product_id;
        $wasPrimary = $productImage->is_primary;

        // Delete physical file from storage
        if ($productImage->image_path && Storage::disk('public')->exists($productImage->image_path)) {
            Storage::disk('public')->delete($productImage->image_path);
        }

        $productImage->delete();

        // If it was primary, designate the first remaining image as primary
        if ($wasPrimary) {
            $next = ProductImage::where('product_id', $productId)->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }
}
