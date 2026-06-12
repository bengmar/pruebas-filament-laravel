<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Province;
use App\Models\UserAddress;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Traemos el carrito del usuario autenticado con sus productos
        $cart = $request->user()->cart()->with('items.product')->first();

        // Si el carrito no existe o no tiene productos, lo mandamos de vuelta al catálogo
        if (!$cart || $cart->items->isEmpty()) {
            // CORREGIDO: Cambiado swal_error por cart_error
            return redirect()->route('catalog')->with('cart_error', 'Tu carrito está vacío. Añade productos antes de finalizar la compra.');
        }

        // =========================================================================
        // CONTROL DE SEGURIDAD 1: Limpieza antes de renderizar la vista Checkout
        // =========================================================================
        $productosEliminados = false;
        foreach ($cart->items as $item) {
            $product = $item->product;

            // Si el producto fue desactivado o borrado mientras miraba el carrito
            if (!$product || !$product->active) {
                $item->delete();
                $productosEliminados = true;
            }
        }

        if ($productosEliminados) {
            $cart->load('items.product'); // Recargamos el carrito limpio

            if ($cart->items->isEmpty()) {
                // Cambiado swal_error por cart_error
                return redirect()->route('catalog')->with('cart_error', 'Los productos de tu carrito ya no están disponibles.');
            }

            // Cambiado swal_error por cart_error y redirigimos al catálogo para asegurar el impacto visual
            return redirect()->route('catalog')->with('cart_error', 'Algunos productos ya no están disponibles y fueron removidos del carrito.');
        }

        // =========================================================================
        // CONTROL DE SEGURIDAD 2: Validación de Stock en tiempo real (Usuario A vs Usuario B)
        // =========================================================================
        $huboCambiosDeStock = false;

        foreach ($cart->items as $item) {
            $product = $item->product;

            // Caso 1: El stock quedó en 0 absoluto debido a la compra del Usuario B
            if ($product->stock <= 0) {
                $item->delete(); // Removemos el ítem porque ya no hay nada
                $huboCambiosDeStock = true;
            } 
            // Caso 2: Queda stock, pero es MENOS de lo que el Usuario A quiere comprar
            // Ej: El usuario A quiere 2, pero el usuario B compró y ahora solo queda 1 en stock
            elseif ($product->stock < $item->quantity) {
                $item->update(['quantity' => $product->stock]); // Le ajustamos la cantidad al máximo disponible
                $huboCambiosDeStock = true;
            }
        }

        // Si detectamos que el stock cambió por culpa de otra compra simultánea:
        if ($huboCambiosDeStock) {
            // Redirigimos al catálogo o al home y enviamos una sesión especial para activar el SweetAlert
            return redirect()->route('catalog')->with('stock_changed_error', 'Hubo cambios en el stock de algunos productos de tu carrito. Por favor, revisa las cantidades antes de continuar.');
        }

        // Si todo es correcto, traemos las direcciones del usuario
        $direcciones = UserAddress::where('user_id', $request->user()->id)
            ->with('city.province')
            ->get();

        // Cargamos todas las provincias por si quiere agregar una nueva dirección en el momento
        $provincias = Province::orderBy('name')->get();

        // Retornamos la vista checkout.blade.php pasándole el carrito
        return view('pages.private.checkout', compact('cart', 'direcciones', 'provincias'));
    }

    public function store(CheckoutRequest $request)
    {
        // 1. Validar los datos que vienen del formulario del checkout
        $validated = $request->validated();

        $user = $request->user();

        // Variables donde guardaremos la info final del envío
        $calleEnvio = '';
        $cpEnvio = '';
        $ciudadEnvioId = null;

        if ($request->user_address_id === 'nueva_direccion') {
            $nuevaDireccion = $user->addresses()->create([
                'alias'       => $request->delivery_alias ?? 'Dirección de Compra',
                'street'      => $request->delivery_street,
                'postal_code' => $request->delivery_postal_code,
                'city_id'     => $request->delivery_city_id,
                'is_default'  => $user->addresses()->count() === 0
            ]);

            $calleEnvio    = $nuevaDireccion->street;
            $cpEnvio       = $nuevaDireccion->postal_code;
            $ciudadEnvioId = $nuevaDireccion->city_id;
        } else {
            $direccionExistente = $user->addresses()->findOrFail($request->user_address_id);

            $calleEnvio    = $direccionExistente->street;
            $cpEnvio       = $direccionExistente->postal_code;
            $ciudadEnvioId = $direccionExistente->city_id;
        }

        // Obtener el carrito del usuario con sus productos
        $cart = $request->user()->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('catalog')->with('cart_error', 'No se pudo procesar la compra porque tu carrito está vacío.');
        }

        // 2. Usamos una Transacción de Base de Datos
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsToCreate = [];
            $huboCambiosDeStock = false;

            // 3. Iterar los productos del carrito para calcular totales y verificar Stock
            foreach ($cart->items as $item) {
                $product = $item->product;

                // Caso A: El producto se desactivó o eliminó de la tienda mientras el usuario rellenaba sus datos
                if (!$product || !$product->active) {
                    $item->delete(); 
                    $huboCambiosDeStock = true;
                    continue; 
                }

                // Caso B: El usuario B compró antes y dejó al usuario A sin el stock necesario
                if ($product->stock < $item->quantity) {
                    $huboCambiosDeStock = true;

                    if ($product->stock <= 0) {
                        $item->delete(); 
                    } else {
                        $item->update(['quantity' => $product->stock]); 
                    }
                    continue;
                }

                // --- LOGICA DE PROCESAMIENTO REUBICADA CORRECTAMENTE ---
                // Si el producto pasó los controles de stock, calculamos sus subtotales de forma normal
                $precioUnitario = $product->final_price;
                $subtotal += $precioUnitario * $item->quantity;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item->quantity,
                    'price'      => $precioUnitario,
                ];
            } 

            // --- RESPUESTA DE SEGURIDAD ---
            // Si detectamos cualquier alteración en los productos o stocks, cancelamos la creación de la orden
            if ($huboCambiosDeStock) {
                DB::commit(); // Guardamos los cambios de reajuste del carrito
                return redirect()->route('catalog')->with('stock_changed_error', 'Hubo cambios en el stock o disponibilidad de los productos mientras completabas tus datos. Tu carrito ha sido actualizado, por favor revísalo.');
            }

            // 4. Crear la Cabecera de la Orden
            $order = Order::create([
                'user_id'              => $request->user()->id,
                'total'                => $subtotal,
                'payment_method'       => $request->paymentMethod,
                'status'               => 'processing',
                'customer_name'        => $request->customer_name,
                'customer_lastname'    => $request->customer_lastname,
                'customer_email'       => $request->customer_email,
                'delivery_street'      => $calleEnvio,
                'delivery_postal_code' => $cpEnvio,
                'delivery_city_id'     => $ciudadEnvioId,
            ]);

            // 5. Crear los ítems de la orden y restar stock de los productos
            foreach ($itemsToCreate as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                $product = Product::find($itemData['product_id']);
                $product->decrement('stock', $itemData['quantity']);
            }

            // 6. Vaciar el carrito de compras del usuario por completo
            $cart->items()->delete();
            $cart->delete();

            DB::commit(); // Confirmamos la compra de forma exitosa

            return redirect()->route('orders.success', $order->id)->with('cart_success', '¡Tu pedido ha sido procesado con éxito!');
            
        } catch (\Exception $e) {
            DB::rollBack(); // Ante cualquier error inesperado de código, deshacemos cambios
            return redirect()->route('catalog')->with('cart_error', 'Ocurrió un error inesperado al procesar tu pedido: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::user()->id) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        return view('pages.private.order-success', compact('order'));
    }
}
