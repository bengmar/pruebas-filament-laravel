<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Agrupamos navbar y footer en un array.
        // La consulta SOLO se ejecutará cuando uno de estos dos componentes se vaya a renderizar.
        View::composer(['components.navbar', 'components.footer'], function ($view) {
            $categorias = Category::query()
                ->where('active', true)
                ->orderByRaw('id = 1 ASC')
                ->orderBy('name', 'asc')
                ->get();

            $view->with('categorias', $categorias);
        });
    }
}
