<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Display the product catalog with filtering, searching, and sorting.
     */
    public function index(Request $request): View
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->get();

        $selectedCategory = $request->input('kategori', $request->input('category'));
        $searchQuery = $request->input('q');
        $selectedType = $request->input('tipe', $request->input('type'));
        $selectedColor = $request->input('warna', $request->input('color'));
        $selectedSort = $request->input('sort', 'terbaru');

        $themeColors = Product::THEME_COLORS;

        $products = Product::active()
            ->with(['category', 'images'])
            ->filter([
                'q' => $searchQuery,
                'kategori' => $selectedCategory,
                'tipe' => $selectedType,
                'warna' => $selectedColor,
                'sort' => $selectedSort,
            ])
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', compact(
            'categories',
            'products',
            'themeColors',
            'selectedCategory',
            'searchQuery',
            'selectedType',
            'selectedColor',
            'selectedSort'
        ));
    }
}
