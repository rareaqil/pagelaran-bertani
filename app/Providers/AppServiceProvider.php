<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Binafy\LaravelCart\Models\Cart;

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
        // Polymorphic Map
        Relation::morphMap([
            'Order' => Order::class, // Perhatikan huruf besar O
        ]);

        // Global cartCount untuk semua view
        View::composer('*', function ($view) {
            $userId = Auth::id() ?? 1;
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
            $items = $cart->items; // relasi ke CartItem
            $cartCount = $items->sum('quantity'); // total qty

            $view->with('cartCount', $cartCount);
        });
    }
}