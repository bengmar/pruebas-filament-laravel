<x-layouts.layout>
    <x-slot name="title">Página No Encontrada (404)</x-slot>

    <div class="container py-5 my-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                {{-- Ilustración de la guitarra con badge de No Encontrado --}}
                <div class="position-relative d-inline-block mb-4">
                    <img src="{{ asset('images/guitarra_error404.png') }}"
                         alt="Error 404 - No Encontrado"
                         class="img-fluid rounded-4 shadow-sm border border-secondary-subtle p-2 bg-body"
                         style="max-width: 320px;">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary fs-6 shadow px-3">
                        404
                    </span>
                </div>

                {{-- Mensaje de Error --}}
                <h1 class="fw-black display-5 color-adaptativo mb-3">¡Acorde perdido! 🎸</h1>
                <p class="fs-5 text-muted-adaptativo mb-4 px-3">
                    La página que estás buscando no existe, fue movida de escenario o cambió de nombre.
                </p>

                <hr class="checkout-separator my-4 w-25 mx-auto">

                {{-- Acciones --}}
                <a href="{{ route('home') }}" class="btn-brand mt-3 d-inline px-4 text-decoration-none">
                    Volver al inicio
                </a>

            </div>
        </div>
    </div>
</x-layouts.layout>
