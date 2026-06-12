<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Traemos los pedidos del usuario autenticado con sus ítems y productos
        $orders = $request->user()->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.private.my-orders', compact('orders'));
    }
}
