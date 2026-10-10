<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale', 'id'));

        View::composer('*', function ($view) {
            $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0;
            $view->with('cartCount', (int) $cartCount);
        });
    }
}
