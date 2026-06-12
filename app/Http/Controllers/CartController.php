<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Muestra los productos que el usuario tiene en su carrito.
     */
    public function index(Request $request)
    {
        // Traemos el carrito del usuario con sus ítems y los datos del producto cargados
        $cart = $request->user()->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with([
                'message' => 'Tu carrito está vacío',
                'items' => []
            ]);
        }

        $mensajesAjuste = [];

        foreach ($cart->items as $item) {
            $product = $item->product;

            // ==================================================================================
            // CASO 0: El producto ya no existe, está inactivo (active = false) o con soft-delete
            // ==================================================================================
            if (!$product || !$product->active) {
                $mensajesAjuste[] = "Uno de los productos ya no está disponible y fue removido de tu carrito.";
                $item->delete(); // Se borra de MariaDB automáticamente para que no vuelva a molestar
                continue; // Saltamos al siguiente ítem del carrito
            }

            // CASO 1: El producto directamente ya no tiene stock (Agotado)
            if ($product->stock <= 0) {
                $mensajesAjuste[] = "El producto '{$product->title}' se ha agotado y fue removido de tu carrito.";
                $item->delete();
                continue;
            }

            // CASO 2: Hay stock, pero es menor a la cantidad que el usuario tenía guardada
            if ($product->stock < $item->quantity) {
                $mensajesAjuste[] = "El stock de '{$product->title}' cambió. Ajustamos la cantidad al máximo disponible ({$product->stock} unidades).";

                // Actualizamos el registro en MariaDB al tope real
                $item->update(['quantity' => $product->stock]);
            }
        }

        // Si hubo cambios, volvemos a cargar la relación limpia para la vista
        if (!empty($mensajesAjuste)) {
            $cart->load('items.product');
            // Guardamos los avisos en la sesión para que Blade los muestre
            session()->flash('cart_updates', $mensajesAjuste);
        }

        return back()->with([
            'cart_id' => $cart->id,
            'items' => $cart->items
        ]);
    }

    /**
     * Agrega un producto al carrito o incrementa su cantidad si ya existe.
     */

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();

        $product = Product::findOrFail($request->product_id);

        //Valida que tenga suficiente stock para ingresar al carrito
        if ($product->stock < $request->quantity) {
            return back()->with('cart_error', 'No hay stock suficiente del producto.');
        }

        //Obtiene el carrito. Si no existe, lo crea
        $cart = $user->cart()->firstOrCreate(
            ['user_id' => $user->id], // Condición de búsqueda
            ['user_id' => $user->id]  // Datos para insertar si no existe
        );

        //Buscamos si el producto esta en el carrito
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            //Al existir, se valida stock y se acumula
            if ($product->stock < ($cartItem->quantity + $request->quantity)) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'No puedes agregar el producto, supera el stock disponible.'], 422);
                }
                return back()->with('cart_error', 'No puedes agregar el producto, supera el stock disponible.');
            }
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // Si es nuevo, lo creamos
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        // SI LA PETICIÓN ES AJAX (NUESTRO NUEVO FETCH)
        if ($request->wantsJson()) {
            // Volvemos a cargar el carrito para tener los datos actualizados listos
            $cart->load('items.product');

            $subtotalGeneral = 0;
            foreach ($cart->items as $item) {
                $subtotalGeneral += $item->product->final_price * $item->quantity;
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto añadido al carrito.',
                'total_items_count' => $cart->items->sum('quantity'), // Nuevo contador general
                'formatted_subtotal' => '$' . number_format($subtotalGeneral, 0, ',', '.'),
                // Si necesitas re-renderizar todo el HTML del offcanvas, luego te enseño un truco,
                // pero por ahora devolvemos los datos duros
            ]);
        }

        // Al momento de retornar la respuesta:
        if ($request->input('action') === 'buy_now') {
            // Si presionó "Finalizar Compra", va directo al checkout
            return redirect()->route('checkout');
        }
        // Redirecciona a la misma página donde estaba el usuario y envía un mensaje de éxito
        return back()->with('cart_success', 'Producto añadido al carrito.');
    }

    /**
     * Actualiza la cantidad exacta de un artículo en el carrito.
     */
    public function updateQuantity(Request $request, int $itemId)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Buscamos el ítem asegurándonos de que pertenezca al carrito del usuario logueado
        $cartItem = CartItem::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($itemId);

        // Validar stock del producto con la nueva cantidad solicitada
        if ($cartItem->product->stock < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No puedes actualizar a esa cantidad, supera el stock disponible.'], 422);
            }
            return back()->with('cart_error', 'No puedes actualizar a esa cantidad, supera el stock disponible.');
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        // SI LA PETICIÓN ES AJAX/JSON (NUESTRO JS NUEVO)
        if ($request->wantsJson()) {
            // Obtenemos el carrito actualizado con todos sus ítems para recalcular el subtotal general
            $cart = $request->user()->cart()->with('items.product')->first();

            $subtotalGeneral = 0;
            foreach ($cart->items as $item) {
                $subtotalGeneral += $item->product->final_price * $item->quantity;
            }

            // Calculamos el total específico de ESTE ítem que se acaba de modificar
            $nuevoItemTotal = $cartItem->product->final_price * $cartItem->quantity;
            $totalQuantity = $cart->items->sum('quantity');

            return response()->json([
                'success' => true,
                'item_total' => $nuevoItemTotal, // Ej: 150000
                'subtotal' => $subtotalGeneral,   // Ej: 450000
                'total_quantity' => $totalQuantity,

                'formatted_item_total' => '$' . number_format($nuevoItemTotal, 0, ',', '.'),
                'formatted_subtotal' => '$' . number_format($subtotalGeneral, 0, ',', '.')
            ]);
        }

        return back()->with('cart_success', 'Cantidad actualizada correctamente.');
    }

    /**
     * Elimina por completo un producto del carrito.
     */

    public function removeItem(Request $request, int $itemId)
    {
        // Buscamos el ítem protegiendo que sea del usuario logueado
        $cartItem = CartItem::whereHas('cart', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($itemId);

        $cartItem->delete();

        // Regresa a la pantalla anterior actualizando el carrito
        return back()->with('cart_success', 'Producto removido del carrito.');
    }

    public function clear(Request $request)
    {
        $cart = $request->user()->cart()->first();

        if ($cart) {
            // Elimina todos los ítems vinculados a este carrito
            $cart->items()->delete();
        }

        return back()->with('cart_success', 'El carrito se ha vaciado por completo.');
    }
}
