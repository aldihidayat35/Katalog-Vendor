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
        $banners = Banner::active()->orderBy('sort_order')->get();

        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::active()
            ->whereIn('slug', [
                'royal-palace-acrylic-hardcover',
                'neraya',
                'maharani-velvet-wax-seal',
                'lumina',
                'amora-foil-envelope-suite',
                'sakura',
                'arunika-minimalist-suite',
                'velora',
                'madani',
                'sultanate-laser-cut-hardbox',
            ])
            ->with(['category', 'images'])
            ->get();

        if ($featuredProducts->count() < 4) {
            $featuredProducts = Product::active()
                ->featured()
                ->with(['category', 'images'])
                ->latest('id')
                ->take(10)
                ->get();
        }

        $catalogProducts = Product::active()
            ->with(['category', 'images'])
            ->latest('id')
            ->take(24)
            ->get();

        $heroBg = setting('hero_bg_image', 'images/landing/hero-bg.jpg');
        $ctaBg = setting('cta_bg_image', 'images/landing/cta-bg.jpg');

        $templatesJson = $catalogProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'type' => $product->type,
                'type_label' => $product->type === 'printed' ? 'Cetak Fisik' : 'Digital Web',
                'category' => $product->category->name ?? 'Pernikahan',
                'category_slug' => $product->category->slug ?? 'pernikahan',
                'price' => rupiah($product->final_price),
                'raw_price' => $product->final_price,
                'description' => $product->short_description ?: $product->description,
                'image' => $product->primary_image_url,
                'rating' => '4.9',
                'reviews' => 280,
                'badge' => $product->badge,
                'detail_url' => route('product.show', $product->slug),
            ];
        })->values();

        $featuredJson = $featuredProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'type' => $product->type,
                'type_label' => $product->type === 'printed' ? 'Cetak Fisik' : 'Digital Web',
                'category' => $product->category->name ?? 'Pernikahan',
                'image' => $product->primary_image_url,
                'rating' => '4.9',
                'detail_url' => route('product.show', $product->slug),
            ];
        })->values();

        return view('home', compact(
            'banners',
            'categories',
            'featuredProducts',
            'catalogProducts',
            'heroBg',
            'ctaBg',
            'templatesJson',
            'featuredJson'
        ));
    }
}
