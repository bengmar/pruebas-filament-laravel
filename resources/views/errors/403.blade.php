<x-layouts.layout>
    <x-slot name="title">Acceso Denegado (403)</x-slot>

    <div class="container py-5 my-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                {{-- Ilustración de la guitarra con badge de Prohibido --}}
                <div class="position-relative d-inline-block mb-4">
                    <img src="{{ asset('images/guitarra_error404.png') }}" alt="Error 403 - Acceso Denegado"
                        class="img-fluid rounded-4 shadow-sm border border-secondary-subtle p-2 bg-body"
                        style="max-width: 320px;">
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-6 shadow px-3">
                        403
                    </span>
                </div>

                {{-- Mensaje de Error --}}
                <h1 class="fw-black display-5 color-adaptativo mb-3">¡Falta de ritmo! 🎸</h1>
                <p class="fs-5 text-muted-adaptativo mb-4 px-3">
                    No tenés los permisos necesarios para acceder a este escenario. Esta zona está restringida.
                </p>

                <hr class="checkout-separator my-4 w-25 mx-auto">

                {{-- Acciones --}}
                <a href="{{ route('home') }}" class="btn-brand mt-3 d-inline px-4 text-decoration-none">
                    Volver al inicio
                </a>
            </div>
        </div>
</x-layouts.layout>
