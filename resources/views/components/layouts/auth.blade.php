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
    <link rel="icon" href="{{ asset('icons/ico/icono-soundWave.ico') }}">
    <title>{{ $title ?? 'Identificación' }} - SoundWave</title>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="fondo d-flex align-items-center justify-content-center" style="min-height: 100vh; overflow-x: hidden;">

    {{-- Switch de Tema --}}
    <button id="theme-toggle" class="btn-brand shadow-lg" title="Cambiar modo">
        <!-- Icono Sol: Se muestra cuando estamos en modo oscuro (porque el botón cambiará a claro) -->
        <span id="theme-toggle-light-icon" class="hidden"><i class="fa-solid fa-sun"></i></span>
        <!-- Icono Luna: Se muestra cuando estamos en modo claro -->
        <span id="theme-toggle-dark-icon" class="hidden"><i class="fa-solid fa-moon"></i></span>
    </button>

    {{-- El contenedor donde caerán Login o Registro --}}
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            {{-- Logo Adaptativo --}}
            <h1 class="fw-bold mb-0" style="letter-spacing: -1px;">
                <span class="texto-rojo">SOUNDWAVE</span><span class="color-adaptativo">STORE</span>
            </h1>
            <p class="text-muted-adaptativo small text-uppercase fw-bold" style="letter-spacing: 2px;">Audio & Music Lab
            </p>
        </div>

        {{ $slot }}

        {{-- Footer simple para Auth --}}
        <div class="text-center mt-4">
            <p class="small text-muted-adaptativo">
                &copy; {{ date('Y') }} Soundwave Store Corrientes.
            </p>
        </div>
    </div>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    @include('sweetalert2::index')
</body>

</html>
