<x-layouts.layout>
    <x-slot name="title">Principal</x-slot>
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm text-center">{{ session('success') }}</div>
    @endif
    {{-- Carrusel Adaptativo --}}
    <div id="carouselExampleIndicators" class="carousel slide mb-3 shadow-sm custom-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
        </div>
        
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/carrusel1.webp') }}" class="d-block w-100" alt="carrusel1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel3.webp') }}" class="d-block w-100" alt="carrusel2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel4.webp') }}" class="d-block w-100" alt="carrusel3">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel2.webp') }}" class="d-block w-100" alt="carrusel4">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carrusel6.webp') }}" class="d-block w-100" alt="carrusel5">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    {{-- Secciones de Productos --}}
    @php
        $secciones = [
            ['titulo' => 'Novedades', 'icon' => 'bi-stars', 'datos' => $novedades],
            ['titulo' => 'Ofertas imperdibles', 'icon' => 'bi-tag-fill', 'datos' => $ofertas_home],
            ['titulo' => 'Los más vistos', 'icon' => 'bi-graph-up', 'datos' => $mas_vistos],
        ];
    @endphp

    @foreach ($secciones as $seccion)
        <div class="container pt-2 pb-5">
            {{-- Título de sección adaptativo: quitamos text-white y border-secondary --}}
            <h3 class="mb-4 text-center color-adaptativo border-bottom border-ui-adaptativa pb-3 text-uppercase fw-bold"
                style="letter-spacing: 1px;">
                <i class="bi {{ $seccion['icon'] }} texto-rojo me-2"></i> {{ $seccion['titulo'] }}
            </h3>
            <div class="row justify-content-center g-4 px-2">
                @foreach ($seccion['datos'] as $card)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex justify-content-center">
                        <x-card :card="$card" />
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</x-layouts.layout>
