<x-layouts.layout>
    <x-slot name='title'>¡Pedido Recibido! — #{{ $order->id }}</x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                
                {{-- Tarjeta Principal de Éxito --}}
                <div class="checkout-section-card shadow p-5 animate__animated animate__zoomIn">
                    
                    {{-- Ícono de Check Animado --}}
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>

                    <h2 class="fw-bold color-adaptativo mb-2">¡Gracias por tu compra!</h2>
                    <p class="text-muted-adaptativo mb-4">
                        Tu Orden ha sido recibida con éxito. Puedes ver el estado en <br><strong><a class="text-decoration-none color-dorado-adaptativo"href="{{route('mis-pedidos')}}">Mis Pedidos</a></strong>.
                    </p>

                    {{-- Caja de Detalles del Pedido --}}
                    <div class="p-4 rounded mb-4 text-start bg-superficie-adaptativa shadow-sm border border-ui-adaptativa">
                        <h5 class="fw-bold small text-uppercase color-dorado-adaptativo mb-3">Detalles del Pedido</h5>
                        
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted-adaptativo">Número de pedido:</span>
                            <span class="fw-bold color-adaptativo">#{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted-adaptativo">Método de pago:</span>
                            <span class="fw-bold color-adaptativo text-uppercase">
                                {{$order->payment_method_label}}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted-adaptativo">Enviar a:</span>
                            <span class="fw-bold color-adaptativo">{{ $order->delivery_street }}</span>
                        </div>
                        
                        <hr class="border-ui-adaptativa my-2">
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold color-adaptativo">Total abonado:</span>
                            <span class="fs-5 fw-bold color-dorado-adaptativo">${{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Instrucciones Dinámicas según el método de pago --}}
                    @if($order->payment_method === 'transfer')
                        <div class="alert alert-warning text-start mb-4 small animate__animated animate__fadeIn animate__delay-1s">
                            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>Pasos para completar tu transferencia:</h6>
                            <p class="mb-1">1. Transferí el total a nuestro **Alias: mi.negocio.alias** o **CBU: 000000310000...**</p>
                            <p class="mb-0">2. Envianos el comprobante por WhatsApp o mail indicando tu número de pedido (<strong>#{{ $order->id }}</strong>).</p>
                        </div>
                    @else
                        <div class="alert alert-success text-start mb-4 small animate__animated animate__fadeIn animate__delay-1s">
                            <p class="mb-0">
                                <i class="bi bi-shield-check me-2"></i> ¡Gracias por tu compra! Tu pago fue confirmado. Estamos procesando tu pedido y pronto podrás acceder a tu comprobante.
                            </p>
                        </div>
                    @endif

                    {{-- Botones de Navegación de salida --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('catalog') }}" class="btn-brand py-3 text-uppercase text-decoration-none fw-bold">
                            <i class="bi bi-bag-fill me-2"></i> Seguir Comprando
                        </a>
                        <a href="{{ route('mis-pedidos') }}" class="btn-outline-adaptativo py-2 text-uppercase text-decoration-none small">
                            Ver mis pedidos
                        </a>
                    </div>

                </div> {{-- Fin Card --}}

            </div>
        </div>
    </div>
</x-layouts.layout>
