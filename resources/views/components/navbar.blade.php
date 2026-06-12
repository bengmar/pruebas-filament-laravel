<nav class="navbar navbar-expand-lg border-bottom nav-custom">
    {{-- Agregamos flex-wrap para permitir el salto en móvil, y flex-lg-nowrap para mantenerlo rígido en PC --}}
    <div class="container-fluid d-flex flex-row align-items-center justify-content-between flex-wrap flex-lg-nowrap">

        {{-- Buscador Móvil (Contenedor ajustado) --}}
        <div class="d-flex d-lg-none flex-grow-1 pe-2 position-relative" style="min-width: 0;">
            <form class="d-flex w-100" action="{{ route('search') }}" method="GET">
                <input id="mobile-search" class="form-control form-control-sm rounded-start-pill" type="search"
                    name="query" placeholder="Buscar..." value="{{ request('query') }}" autocomplete="off">
                <button class="btn btn-outline-secondary btn-sm rounded-end-pill" type="submit">
                    <img src="{{ asset('icons/svg/buscar.svg') }}" alt="buscar" width="16" class="icon-adaptive">
                </button>
            </form>
            {{-- Contenedor de sugerencias móvil --}}
            <div id="mobile-search-suggestions" class="list-group position-absolute w-100 shadow d-none"
                style="z-index: 1050; top: 100%; left: 0; padding: 0 1rem;">
            </div>
        </div>

        {{-- Botón Hamburguesa --}}
        <button class="navbar-toggler flex-shrink-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menú Desplegable --}}
        {{-- En móvil (w-100) ocupará toda la línea inferior. En PC vuelve a su comportamiento por defecto --}}
        <div class="collapse navbar-collapse w-100" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto py-2 text-center">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active fw-bold' : '' }}"
                        href="{{ route('home') }}">PRINCIPAL</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('about') ? 'active fw-bold' : '' }}"
                        href="{{ route('about') }}">QUIENES SOMOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('marketing') ? 'active fw-bold' : '' }}"
                        href="{{ route('marketing') }}">COMERCIALIZACIÓN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('contact') ? 'active fw-bold' : '' }}"
                        href="{{ route('contact') }}">CONTACTO</a>
                </li>
                <li class="nav-item">
                    <a @class([
                        'nav-link',
                        'nav-link-custom',
                        'active fw-bold' => request()->routeIs('terms'),
                    ]) href="{{ route('terms') }}">TÉRMINOS DE USO</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle {{ request()->routeIs('catalog') ? 'active fw-bold' : '' }}"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        CATÁLOGO
                    </a>
                    <ul class="dropdown-menu pages-decoration">
                        <li><a @class([
                            'dropdown-item',
                            'item-catalogo',
                            'text-center',
                            'text-lg-start',
                        ]) href="{{ route('catalog') }}">VER TODO</a></li>
                        <li><hr class="dropdown-divider"></li>

                        @foreach ($categorias as $categoria)
                            <li>
                                <a @class([
                                    'dropdown-item',
                                    'item-catalogo',
                                    'text-center',
                                    'text-lg-start',
                                    'overflow-hidden',
                                    'item-catalogo-active' => request()->route('categoria') == $categoria->id,
                                ]) href="{{ route('catalog', $categoria->id) }}">
                                    {{ Str::upper($categoria->display_title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('queries') ? 'active fw-bold' : '' }}"
                        href="{{ route('queries') }}">CONSULTA</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
