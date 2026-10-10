<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the specified product detail page.
     */
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'images'])
            ->first();

        if (! $product || ! $product->is_active || ($product->category && ! $product->category->is_active)) {
            abort(404, 'Produk tidak ditemukan atau sedang tidak aktif.');
        }

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'images'])
            ->take(4)
            ->get();

        return view('product.show', compact('product', 'relatedProducts'));
    }
}
