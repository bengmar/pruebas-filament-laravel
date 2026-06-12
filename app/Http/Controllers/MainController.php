<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $ofertas_home = Product::query()->where('on_sale', true)->take(4)->get();
        $novedades    = Product::query()->latest()->take(4)->get();

        // Consulta directa a la base de datos: simple y limpia
        $mas_vistos = Product::query()
            ->orderBy('views', 'desc') //Prioridad para ordenar
            ->inRandomOrder() //funciona correctamente. En caso de que 2 productos tengan misma cant de vistas
            ->take(4)
            ->get();

        return view('pages.public.home', compact('ofertas_home', 'novedades', 'mas_vistos'));
    }

    public function terms()
    {
        return view('pages.public.term-and-uses');
    }

    public function about()
    {
        return view('pages.public.about');
    }

    public function marketing()
    {
        return view('pages.public.marketing');
    }

    public function contact()
    {
        return view('pages.public.contact');
    }
}
