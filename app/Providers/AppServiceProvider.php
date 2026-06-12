<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
        // Usamos un View Composer para que la base de datos solo se consulte
        // cuando Laravel realmente vaya a renderizar una vista de Blade.
        View::composer('*', function ($view) {
            $cart = null;
            $cartCount = 0;

            // Al ser exclusivo para usuarios logueados, validamos la sesión
            if (Auth::check()) {
                // Buscamos el carrito con sus ítems y productos precargados
                $cart = Cart::where('user_id', Auth::id())
                            ->with('items.product')
                            ->first();

                $cartCount = $cart?->items->sum('quantity') ?? 0;
            }

            // Compartimos la variable $cart con TODAS las vistas de Blade
            $view->with('cart', $cart);
            $view->with('cartCount', $cartCount);
        });
        
        Paginator::useBootstrapFive();
    }
}
