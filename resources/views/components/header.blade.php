<header class="container-fluid border-bottom py-2 fondo">
    <div class="row align-items-center">

        {{-- logo --}}
        <div class="col-lg-4 col-xl-3 col-sm-12 d-flex justify-content-center fs-3">
            <a class="navbar-brand texto-rojo animate__animated animate__pulse animate__infinite"
                href="{{ route('home') }}">SOUNDWAVE<span class="text-secondary"> STORE</span></a>
        </div>

        {{-- buscador --}}
        <div class="px-4 col-lg-4 col-xl-6 p-2 d-none d-lg-block bd-search position-relative">
            <form class="d-flex" action="{{ route('search') }}" method="GET">
                <input id="main-search" class="form-control rounded-start-pill" autocomplete="off" type="search"
                    name="query" {{-- El nombre del campo es clave --}} placeholder="Buscá por marca, categoría o instrumento..."
                    value="{{ request('query') }}">
                {{-- Mantiene el texto después de buscar --}}
                <button class="btn btn-outline-secondary rounded-end-pill" type="submit">
                    <img src="{{ asset('icons/svg/buscar.svg') }}" alt="buscar" class="icon-adaptive">
                </button>
            </form>
            <div id="search-suggestions" class="list-group position-absolute w-100 shadow d-none"
                style="z-index: 1050; top: 100%;"></div>
        </div>

        {{-- carrito e inicio de sesion --}}
        <div class="col-lg-4 col-xl-3 d-flex justify-content-center align-items-center">

            @guest
                {{-- LO QUE VE EL USUARIO NO LOGUEADO --}}
                <div class="d-flex align-items-center me-3">
                    <a class="d-flex align-items-center text-decoration-none" href="{{ route('login') }}">
                        <img src="{{ asset('icons/svg/persona.svg') }}" alt="Iniciar Sesion" class="icon-adaptive">
                    </a>

                    <div class="d-flex flex-column d-none d-lg-flex text-center" style="line-height: 1.2;">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <span class="fw-semibold color-adaptativo p-lg-1">Iniciar Sesión</span>
                        </a>
                        <a href="{{ route('signup.create') }}" class="text-decoration-none">
                            <small class="text-muted-adaptativo">¿Eres Nuevo?</small>
                        </a>
                    </div>
                </div>
            @endguest

            @auth
                {{-- LO QUE VE EL USUARIO LOGUEADO --}}
                <div class="d-flex align-items-center me-3">
                    <div class="dropdown">
                        <a class="d-flex align-items-center nav-link-custom text-decoration-none dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('icons/svg/persona.svg') }}" alt="Usuario" class="icon-adaptive me-2">
                            <div class="d-flex flex-column d-none d-lg-flex text-start" style="line-height: 1.2;">
                                <span class="fw-semibold color-adaptativo">Hola, {{ auth()->user()->first_name }}</span>
                                <small class="text-muted-adaptativo text-center">
                                    {{ auth()->user()->role_id === 1 ? 'Administrador' : 'Mi Cuenta' }}
                                </small>
                            </div>
                        </a>

                        <ul class="dropdown-menu shadow">
                            @if (auth()->user()->role_id === 1)
                                {{-- MENÚ EXCLUSIVO PARA ADMINISTRADOR --}}
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}">
                                        Ir a panel Admin
                                    </a>
                                </li>
                            @else
                                {{-- MENÚ PARA USUARIOS REGULARES --}}
                                <li>
                                    <a class="dropdown-item" href="{{ url('/panel-usuario') }}">
                                        Mis Datos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/mis-pedidos') }}">
                                        Mis Pedidos
                                    </a>
                                </li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                {{-- Formulario de Logout para ambos --}}
                                <form action="{{ route('logout') }}" method="POST" class="px-3 py-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger w-100">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

            {{-- Carrito: Solo se muestra si es invitado O si está logueado pero NO es admin --}}
            @if (!auth()->check() || auth()->user()->role_id !== 1)
                <a href="#" class="text-decoration-none px-2 position-relative" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasCart">
                    <img src="{{ asset('icons/svg/carrito.svg') }}" alt="carrito" class="icon-adaptive">

                    {{-- Eliminamos el @if de Blade y dejamos el span siempre disponible --}}
                    <span id="global-cart-badge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger global-cart-badge {{ isset($cartCount) && $cartCount > 0 ? '' : 'd-none' }}"
                        style="font-size: 0.5rem;">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>
            @endif
        </div>
    </div>
</header>
@error('query')
    {{-- Si la validación de búsqueda falla --}}
    <div class="alert alert-danger border-0 shadow-sm text-center">
        {{ $message }}
    </div>
@enderror
