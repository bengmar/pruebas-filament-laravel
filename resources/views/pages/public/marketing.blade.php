<x-layouts.layout>
    <x-slot name="title">Comercialización</x-slot>

    <div class="container p-2 p-md-4 px-lg-5">
        {{-- Eliminamos text-light para que use var(--color-texto) --}}
        <div class="pages-decoration my-4 px-3 px-md-5 pb-5">

            <h2 class="text-center border-bottom border-ui-adaptativa py-4 mb-5 color-adaptativo">Comercialización</h2>

            <div class="row align-items-center mb-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('images/comercializacion.webp') }}" alt="Comercialización Soundwave Store"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
                <div class="col-md-6">
                    <p class="color-adaptativo">En <strong class="texto-rojo text-uppercase">Soundwave Store</strong>
                        desarrollamos un modelo de comercialización integral orientado a garantizar eficiencia,
                        transparencia y sostenibilidad en cada operación.</p>
                    <p class="color-adaptativo">Nuestra gestión se basa en procesos estandarizados que permiten
                        optimizar la cadena de valor, desde la adquisición de instrumentos hasta la entrega final al
                        cliente.</p>
                </div>
            </div>

            {{-- Estrategia de Abastecimiento --}}
            <div class="row align-items-center mb-5 py-4 border-top border-ui-adaptativa">
                <div class="col-md-6 order-2 order-md-1">
                    <h3 class="mb-3 color-adaptativo">
                        <i class="bi bi-box-seam me-2" style="color:var(--color-seis);"></i>Estrategia de Abastecimiento
                    </h3>
                    <p class="color-adaptativo">Trabajamos con proveedores nacionales e internacionales cuidadosamente
                        seleccionados, priorizando la calidad, la trazabilidad y la continuidad en el suministro.</p>
                </div>
                <div class="col-md-6 order-1 order-md-2 mb-4 mb-md-0">
                    <img src="{{ asset('images/proveedores.webp') }}" alt="Proveedores de instrumentos"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
            </div>

            {{-- Canales de Venta --}}
            <div class="row align-items-center mb-5 py-4 border-top border-ui-adaptativa">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('images/ecommerce.png') }}" alt="E-commerce Soundwave Store"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
                <div class="col-md-6">
                    <h3 class="mb-3 color-adaptativo">
                        <i class="bi bi-cart-fill me-2" style="color:var(--color-dos);"></i>Canales de Venta
                    </h3>
                    <ul class="list-unstyled color-adaptativo">
                        <li class="mb-3">
                            <strong class="texto-rojo">E-commerce propio:</strong>
                            Plataforma digital con alcance nacional, diseñada para ofrecer una experiencia de compra
                            segura y eficiente.
                        </li>
                        <li>
                            <strong class="texto-rojo">Distribución regional:</strong>
                            Presencia activa en Corrientes y provincias aledañas, con una logística adaptada al mercado
                            local.
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Precios y Logística --}}
            <div class="row align-items-center mb-5 py-4 border-top border-ui-adaptativa">
                <div class="col-md-6 order-2 order-md-1">
                    <h3 class="mb-3 color-adaptativo">
                        <i class="bi bi-credit-card-fill me-2" style="color:var(--color-seis);"></i>Precios y Logística
                    </h3>
                    <p class="color-adaptativo">Implementamos una política de precios transparente y competitiva,
                        acompañada de opciones de financiación flexibles.</p>
                </div>
                <div class="col-md-6 order-1 order-md-2 mb-4 mb-md-0">
                    <img src="{{ asset('images/logistica.png') }}" alt="Logística y distribución"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
            </div>

            {{-- Compromiso Corporativo --}}
            <div class="pt-4 border-top border-ui-adaptativa">
                <h2 class="text-center mb-5 color-adaptativo text-uppercase">Nuestro Compromiso Corporativo</h2>
                <div class="row justify-content-center">
                    @php
                        $compromisos = [
                            [
                                'titulo' => 'Eficiencia Operativa',
                                'icon' => 'bi-gear-fill',
                                'desc' =>
                                    'Optimización constante de nuestros procesos internos para reducir tiempos de respuesta.',
                            ],
                            [
                                'titulo' => 'Transparencia',
                                'icon' => 'bi-shield-check',
                                'desc' =>
                                    'Mantenemos una comunicación clara y cumplimiento estricto de las condiciones pactadas.',
                            ],
                            [
                                'titulo' => 'Sostenibilidad',
                                'icon' => 'bi-globe',
                                'desc' =>
                                    'Desarrollamos prácticas responsables que favorecen el crecimiento de la comunidad musical.',
                            ],
                        ];
                    @endphp

                    @foreach ($compromisos as $item)
                        <div class="col-md-4 mb-4">
                            <div class="card team-card h-100 shadow-sm text-center p-4">
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center border-ui-adaptativa"
                                    style="width: 80px; height: 80px; background: var(--color-uno);">
                                    <i class="bi {{ $item['icon'] }} fs-2 texto-rojo"></i>
                                </div>
                                <h5 class="card-title texto-rojo">{{ $item['titulo'] }}</h5>
                                <p class="card-text small color-adaptativo">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-layouts.layout>
