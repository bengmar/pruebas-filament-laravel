<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller

{

    public function index($categoriaId = null)
    {
        // 1. Iniciamos la consulta base cargando la relación para evitar el problema N+1
        $query = Product::with('category');
        $tituloCategoria = 'Nuestro Catálogo'; // Título por defecto si no hay filtro

        if ($categoriaId) {
            // 2. Buscamos la categoría primero. Si no existe, lanzamos un 404 automáticamente.
            $categoriaActual = Category::findOrFail($categoriaId);

            // 3. Usamos el operador de fusión de nulidad (??) para el título
            $tituloCategoria = $categoriaActual->display_title ?? $categoriaActual->name;

            // 4. Filtramos los productos directamente por el ID de la relación
            $query->where('category_id', $categoriaId);
        }

        // 5. Traemos los productos ( get() o paginate(12))
        $products = $query->paginate(8);
        $categoria = $categoriaId;

        return view('pages.public.catalog', compact('products', 'tituloCategoria', 'categoria'));
    }

    public function details(int $id)
    {
        $product = Product::findOrFail($id);

        // Lista de productos ya vistos desde la sesión (si no existe, empezamos un array vacío)
        $vistos = session()->get('productos_vistos', []);

        // Si el ID de este producto NO está en el array, sumamos la visita y lo agregamos
        if (!in_array($id, $vistos)) {
            $product->increment('views'); // Suma +1 de forma segura

            session()->push('productos_vistos', $id); // Guarda este ID en la sesión
        }
        //el final_price lo calcula getFinalPriceAttribute() en el modelo
        $cantidadEnCarrito = 0;

        // 2. CORRECCIÓN: Usamos Auth::check() que es 100% seguro
        if (Auth::check()) {
            $user = Auth::user(); // También podés usar Auth::user() aquí de forma segura

            // Buscamos si ya tiene este producto en el carrito
            $cartItem = \App\Models\CartItem::whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('product_id', $id)->first();

            if ($cartItem) {
                $cantidadEnCarrito = $cartItem->quantity;
            }
        }

        // 3. Calculamos el stock disponible temporal
        $stockDisponible = max(0, $product->stock - $cantidadEnCarrito);

        return view('pages.public.product-details', compact('product', 'stockDisponible'));
    }
}
