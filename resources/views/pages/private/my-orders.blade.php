<x-layouts.layout>
    <x-slot name='title'>Mis Pedidos</x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <h3 class="fw-bold color-adaptativo mb-4">
                    <i class="bi bi-box-seam me-2 color-dorado-adaptativo"></i>Mi Historial de Pedidos
                </h3>

                @if ($orders->count() > 0)
                    {{-- Contenedor del Acordeón de Bootstrap --}}
                    <div class="accordion accordion-flush" id="accordionOrders">

                        @foreach ($orders as $index => $order)
                            <div
                                class="card mb-3 border border-ui-adaptativa shadow-sm overflow-hidden bg-superficie-adaptativa rounded">

                                {{-- Encabezado del Pedido (Botón del Acordeón) --}}
                                <div class="p-3" style="cursor: pointer;" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $order->id }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $order->id }}">
                                    <div class="row align-items-center g-3">

                                        <div class="col-6 col-md-3">
                                            <span class="text-muted-adaptativo small d-block">NÚMERO DE PEDIDO</span>
                                            <span class="fw-bold color-adaptativo">#{{ $order->id }}</span>
                                        </div>

                                        <div class="col-6 col-md-3">
                                            <span class="text-muted-adaptativo small d-block">FECHA</span>
                                            <span class="color-adaptativo small">{{ $order->fecha_argentina }}</span>
                                        </div>

                                        <div class="col-6 col-md-3">
                                            <span class="text-muted-adaptativo small d-block">TOTAL</span>
                                            <span
                                                class="fw-bold color-dorado-adaptativo">${{ number_format($order->total, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="col-6 col-md-3 text-md-end">
                                            @if ($order->status === 'pending')
                                                <span
                                                    class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 text-uppercase"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-wallet2 me-1"></i> {{ $order->status_label }}
                                                </span>
                                            @elseif($order->status === 'processing')
                                                <span
                                                    class="badge bg-info-subtle text-info border border-info px-3 py-2 text-uppercase"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-clock-history me-1"></i> {{ $order->status_label }}
                                                </span>
                                            @elseif($order->status === 'shipped')
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 text-uppercase"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-truck me-1"></i> {{ $order->status_label }}
                                                </span>
                                            @elseif($order->status === 'delivered')
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success px-3 py-2 text-uppercase"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-check-circle me-1"></i> {{ $order->status_label }}
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 text-uppercase"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-x-circle me-1"></i> {{ $order->status_label }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                {{-- Contenido Desplegable (Detalles del Pedido) --}}
                                <div id="collapse-{{ $order->id }}" class="collapse {{ $index === 0 ? '' : '' }}"
                                    data-bs-parent="#accordionOrders">
                                    <div class="p-3 border-top border-ui-adaptativa bg-light-adaptativa"
                                        style="background-color: rgba(0,0,0,0.02);">

                                        <h6 class="fw-bold small text-uppercase color-dorado-adaptativo mb-3">Productos
                                            Comprados</h6>

                                        {{-- Iteración de los productos del pedido --}}
                                        <div class="row g-3">
                                            @foreach ($order->items as $item)
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm overflow-hidden item-cart-card">
                                                        <div class="row g-0 align-items-center">

                                                            {{-- Imagen del Producto --}}
                                                            <div class="col-3 col-md-2 d-flex align-items-center justify-content-center p-2"
                                                                style="max-width: 90px;">
                                                                <img src="{{ $item->product->image_1 ? asset('storage/' . $item->product->image_1) : asset('images/piano-casio.webp') }}"
                                                                    class="img-fluid rounded"
                                                                    alt="{{ $item->product->title }}">
                                                            </div>

                                                            {{-- Título y cantidad --}}
                                                            <div class="col-9 col-md-10">
                                                                <div
                                                                    class="card-body py-2 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                                                    <div>
                                                                        <h6 class="card-title mb-1 fw-bold text-uppercase color-adaptativo"
                                                                            style="font-size: 0.85rem;">
                                                                            {{ $item->product->title }}
                                                                        </h6>
                                                                        <small class="text-muted-adaptativo">
                                                                            Precio unitario:
                                                                            ${{ number_format($item->price, 0, ',', '.') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="mt-2 mt-md-0 text-md-end">
                                                                        <span
                                                                            class="text-muted-adaptativo me-3 small">Cantidad:
                                                                            <strong>{{ $item->quantity }}</strong></span>
                                                                        <span
                                                                            class="fw-bold color-dorado-adaptativo">${{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Resumen de datos de envío del pedido --}}
                                        <div
                                            class="mt-4 p-3 rounded border border-ui-adaptativa bg-superficie-adaptativa">
                                            <div class="row g-3 small">
                                                <div class="col-md-6">
                                                    <span
                                                        class="text-muted-adaptativo d-block text-uppercase fw-bold mb-1"
                                                        style="font-size: 0.75rem;">Datos de Entrega</span>
                                                    <p class="mb-1 color-adaptativo"><strong>Destinatario:</strong>
                                                        {{ $order->customer_name }} {{ $order->customer_lastname }}
                                                    </p>
                                                    <p class="mb-0 color-adaptativo"><strong>Dirección:</strong>
                                                        {{ $order->delivery_street }} (CP
                                                        {{ $order->delivery_postal_code }})</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="text-muted-adaptativo d-block text-uppercase fw-bold mb-1"
                                                        style="font-size: 0.75rem;">Pago y Contacto</span>
                                                    <p class="mb-1 color-adaptativo"><strong>Email:</strong>
                                                        {{ $order->customer_email }}</p>
                                                    <p class="mb-0 color-adaptativo"><strong>Método de pago:</strong>
                                                        {{ $order->payment_method_label }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div> {{-- Fin collapse --}}

                            </div> {{-- Fin card de pedido --}}
                        @endforeach

                    </div> {{-- Fin Accordion --}}
                @else
                    {{-- Estado sin pedidos --}}
                    <div
                        class="text-center p-5 bg-superficie-adaptativa rounded shadow-sm border border-ui-adaptativa animate__animated animate__fadeIn">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 fw-bold color-adaptativo">Aún no has realizado pedidos</h5>
                        <p class="text-muted-adaptativo">Cuando realices compras en nuestra tienda, verás el seguimiento
                            aquí.</p>
                        <a href="{{ route('catalog') }}"
                            class="special-btn py-2 px-4 text-decoration-none small inline-block mt-2 ">
                            Ir al Catálogo
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layouts.layout>
