<div class="offcanvas offcanvas-end border-0 shadow cart-custom" tabindex="-1" id="offcanvasCart"
    aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header header-cart-custom">
        <h5 class="offcanvas-title fw-bold" id="offcanvasCartLabel">
            <i class="bi bi-cart3 me-2"></i>TU CARRITO
        </h5>
        <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">

        @php
            $subtotal = 0;
            $huboAjusteStock = false;

            // FILTRADO PREVIO: Limpiamos los productos fantasmas ANTES de evaluar si el carrito está vacío
            if ($cart) {
                foreach ($cart->items as $item) {
                    if (!$item->product || !$item->product->active || $item->product->stock <= 0) {
                        $item->delete();
                    }
                }
                // Recargamos la relación en caliente para que cuente los reales
                $cart->load('items.product');
            }
        @endphp

        {{-- Ahora sí, evaluamos con la realidad de la base de datos --}}

        @if ($cart && $cart->items->count() > 0)

            @foreach ($cart->items as $item)
                @php
                    $producto = $item->product;

                    //Caso producto desactivado o borrado
                    if (!$producto || !$producto->active) {
                        // Persistencia: Lo eliminamos de la base de datos para limpiar el carrito internamente
                        $item->delete();
                        continue; // Saltamos este producto y no renderizamos nada de su tarjeta
                    }

                    $cantidadReal = $item->quantity;
                    $notificacionItem = null;

                    // VALIDACIÓN Y PERSISTENCIA DE STOCK EN TIEMPO REAL (UserA vs UserB)
                    if ($producto->stock <= 0) {
                        // CASO 1: El producto se agotó por completo por culpa de UserA.
                        $notificacionItem = 'agotado';
                        $cantidadReal = 0;

                        // PERSISTENCIA: Lo eliminamos de la base de datos para que no estorbe
                        $item->delete();
                    } elseif ($producto->stock < $item->quantity) {
                        // CASO 2: Queda stock, pero UserA se llevó unidades y superó lo que UserB tenía guardado.
                        $notificacionItem = 'ajustado';
                        $cantidadReal = $producto->stock;
                        $huboAjusteStock = true;

                        // PERSISTENCIA: Actualizamos la fila en MariaDB al máximo disponible actual
                        $item->update(['quantity' => $producto->stock]);
                    }

                    // Cálculos económicos basados en la realidad de la base de datos
                    $precioUnitarioReal = $producto->final_price;
                    $itemTotal = $precioUnitarioReal * $cantidadReal;
                    $subtotal += $itemTotal;
                @endphp

                {{-- Item del Carrito Dinámico --}}
                <div class="card mb-3 border-0 shadow-sm overflow-hidden item-cart-card"
                    data-item-id="{{ $item->id }}" data-product-id="{{ $producto->id }}">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 cart-img-container d-flex align-items-center justify-content-center p-2">
                            <img src="{{ $producto->image_1 ? asset('storage/' . $producto->image_1) : asset('images/piano-casio.webp') }}"
                                class="img-fluid @if ($notificacionItem == 'agotado') opacity-50 @endif"
                                alt="{{ $producto->title }}">
                        </div>
                        <div class="col-8">
                            <div class="card-body py-2">
                                <h6 class="card-title mb-0 fw-bold text-uppercase color-adaptativo"
                                    style="font-size: 0.85rem;">
                                    {{ $producto->title }}
                                </h6>

                                {{-- DETALLE DE PRECIOS SI TIENE DESCUENTO --}}
                                <div class="small my-1">
                                    @if ($producto->on_sale && $producto->discount > 0)
                                        <span class="text-decoration-line-through text-muted-adaptativo me-1"
                                            style="font-size: 0.75rem;">
                                            ${{ number_format($producto->price, 0, ',', '.') }}
                                        </span>
                                        <span class="badge bg-danger-subtle text-danger fw-normal"
                                            style="font-size: 0.65rem;">
                                            {{ $producto->discount }}% OFF
                                        </span>
                                    @endif

                                    {{-- Gestión de alertas visuales de stock concurrentes --}}
                                    @if ($notificacionItem == 'agotado')
                                        <div class="text-danger small fw-bold my-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-x-circle-fill"></i> El producto se ha agotado.
                                        </div>
                                    @elseif($notificacionItem == 'ajustado')
                                        <div class="text-warning small fw-bold my-1"
                                            style="font-size: 0.72rem; line-height: 1.1;">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Stock ajustado al máximo
                                            disponible.
                                        </div>
                                    @endif

                                    {{-- Selector de cantidad interactivo con límites de stock --}}
                                    @if ($producto->stock > 0)
                                        <div class="d-flex align-items-center my-2">
                                            <small class="text-muted-adaptativo me-2">Cantidad:</small>
                                            <div class="input-group input-group-sm" style="max-width: 120px;">
                                                <button class="btn btn-outline-secondary btn-qty-decrement"
                                                    type="button" data-item-id="{{ $item->id }}">-</button>

                                                <input type="number"
                                                    class="form-control text-center input-qty-cart no-spinners"
                                                    value="{{ $cantidadReal }}" min="1"
                                                    max="{{ $producto->stock }}" data-item-id="{{ $item->id }}"
                                                    data-initial-value="{{ $cantidadReal }}">

                                                <button class="btn btn-outline-secondary btn-qty-increment"
                                                    type="button" data-item-id="{{ $item->id }}">+</button>
                                            </div>
                                        </div>
                                        {{-- Alerta dinámica de teclado --}}
                                        <div class="text-warning small fw-bold d-none label-stock-max"
                                            style="font-size: 0.7rem;">
                                            Máximo disponible alcanzado
                                        </div>
                                        <div class="text-danger small fw-bold d-none label-type-err"
                                            style="font-size: 0.7rem;">
                                            Solo números enteros
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold color-dorado-adaptativo item-total-display">
                                        ${{ number_format($itemTotal, 0, ',', '.') }}
                                    </span>

                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn p-0 text-danger border-0 bg-transparent texto-rojo">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="my-4 border-top border-ui-adaptativa"></div>

            {{-- Resumen de Compra Dinámico --}}
            <div class="p-3 bg-superficie-adaptativa rounded shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted-adaptativo">Subtotal</span>
                    <span
                        class="fw-bold color-adaptativo cart-subtotal-display">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted-adaptativo">Envío</span>
                    <span class="text-success fw-bold">Gratis</span>
                </div>
                <hr class="border-ui-adaptativa">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fs-5 fw-bold color-adaptativo">TOTAL:</span>
                    <span
                        class="fs-5 fw-bold color-adaptativo cart-total-display">${{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="d-grid gap-2">
                    {{-- Si hubo productos agotados que bajaron a 0, se puede deshabilitar el botón de compra o dejar que se limpie en el controlador --}}
                    <a href="{{ route('checkout') }}"
                        class="btn-brand text-uppercase py-3 text-decoration-none text-center">
                        Confirmar carrito
                    </a>

                    <form id="form-vaciar-carrito" action="{{ route('cart.clear') }}" method="POST" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="btn-vaciar-carrito"
                            class="btn btn-outline-danger text-uppercase py-2" style="font-size: 0.85rem;">
                            <i class="bi bi-trash3 me-1"></i> Vaciar Carrito
                        </button>
                    </form>
                    <a href="{{ route('catalog') }}"
                        class="btn btn-link text-muted-adaptativo text-decoration-none text-uppercase"
                        style="font-size: 0.8rem;">
                        Continuar comprando
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted-adaptativo">No tienes productos en tu carrito de compras.</p>
                <a href="{{ route('catalog') }}" class="btn btn-outline-secondary btn-sm mt-2">
                    Ir a tienda
                </a>
            </div>
        @endif
    </div>
</div>
