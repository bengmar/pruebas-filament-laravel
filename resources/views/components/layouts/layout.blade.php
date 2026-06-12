<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <!-- SCRIPT ANTI-PARPADEO -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Si no hay nada guardado, usamos el sistema. Si hay, usamos lo guardado.
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

            if (theme === 'dark') {
                document.documentElement.classList.remove('light');
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('icons/ico/icono-soundWave.ico') }}">
    <title>{{ $title ?? 'Inicio' }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="fondo d-flex flex-column min-vh-100">
    <x-header />
    {{-- ALERTA FLOTANTE (CART / SYSTEM FLASH) --}}
    @if (session('cart_success') || session('cart_error'))
        <div id="flash-message"
            class="position-fixed bottom-0 start-0 m-3 shadow rounded px-3 py-2 text-white
            {{ session('cart_success') ? 'bg-success' : 'bg-danger' }}
            animate__animated animate__fadeInUp"
            style="z-index: 9999; min-width: 280px; max-width: 350px;">

            <div class="d-flex align-items-center gap-2">
                <i class="bi {{ session('cart_success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }}"></i>

                <span class="small">
                    {{ session('cart_success') ?? session('cart_error') }}
                </span>

                <button type="button"
                    class="btn-close btn-close-white ms-auto"
                    onclick="document.getElementById('flash-message').remove()">
                </button>
            </div>
        </div>

        <script>
            setTimeout(() => {
                const el = document.getElementById('flash-message');
                if (el) {
                    el.classList.add('animate__fadeOutDown');
                    setTimeout(() => el.remove(), 500);
                }
            }, 4000);
        </script>
    @endif
    
    <button id="theme-toggle" class="btn-brand shadow-lg" title="Cambiar modo">
        <!-- Icono Sol: Se muestra cuando estamos en modo oscuro (porque el botón cambiará a claro) -->
        <span id="theme-toggle-light-icon" class="hidden"><i class="fa-solid fa-sun"></i></span>
        <!-- Icono Luna: Se muestra cuando estamos en modo claro -->
        <span id="theme-toggle-dark-icon" class="hidden"><i class="fa-solid fa-moon"></i></span>
    </button>

    <x-navbar />

    <main class="grow">
        {{-- ALERTA TRADICIONAL DEL SISTEMA (Login, Logout, etc.) --}}
        @if(session('system_success') || session('system_error'))
            <div class="alert {{ session('system_success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show border-0 rounded-0 text-center shadow-sm mb-0 animate__animated animate__fadeIn" role="alert">
                <i class="bi {{ session('system_success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} me-2"></i>
                {{ session('system_success') ?? session('system_error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{ $slot }}
    </main>

    <x-cart />
    <x-footer />
    {{-- EVALUA SI DISPARAR SWEETALERT DE CARGA --}}
    @if(session('swal_success') || session('swal_error'))
        @include('sweetalert2::index')
    @endif
    
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/flash-message.js') }}"></script>
    <script src="{{ asset('js/cart-updater.js') }}"></script>
</body>

</html>
