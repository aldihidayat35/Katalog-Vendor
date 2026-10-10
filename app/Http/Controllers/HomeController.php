<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): View
    {
        $data = \Illuminate\Support\Facades\Cache::remember('homepage_data', 3600, function () {
            return [
                'banners' => Banner::active()->orderBy('sort_order')->get(),
                'categories' => Category::active()
                    ->withCount(['products' => fn ($q) => $q->active()])
                    ->get(),
                'latestProducts' => Product::active()
                    ->with(['category', 'images'])
                    ->latest('id')
                    ->take(4)
                    ->get(),
                'featuredProducts' => Product::active()
                    ->featured()
                    ->with(['category', 'images'])
                    ->latest('id')
                    ->take(4)
                    ->get(),
            ];
        });

        return view('home', $data);
    }
}
