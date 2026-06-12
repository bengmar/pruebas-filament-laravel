<footer class="container-fluid border-top pt-4 footer-custom">
    <div class="container">

        {{-- DESKTOP --}}
        <div class="row align-items-start py-4 d-none d-md-flex">
            {{-- Categorías --}}
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="texto-rojo text-uppercase mb-3 small fw-bold text-center">Categorías</h5>
                <ul class="list-unstyled text-center">
                    @foreach ($categorias as $categoria)
                        <li class="mb-2">
                            <a class="footer-link text-decoration-none" href="{{ route('catalog', $categoria->id) }}">
                                {{ $categoria->display_title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Navegación --}}
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="texto-rojo text-uppercase mb-3 small fw-bold text-center">Navegación</h5>
                <ul class="list-unstyled text-center">
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('home') }}">Principal</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('about') }}">Quiénes Somos</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('marketing') }}">Comercialización</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('contact') }}">Contacto</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('terms') }}">Términos de Uso</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('catalog') }}">Nuestro Catálogo</a></li>
                    <li><a class="footer-link text-decoration-none" href="{{ route('queries') }}">Consultas</a></li>
                </ul>
            </div>

            {{-- Contacto --}}
            <div class="col-md-4">
                <h5 class="texto-rojo text-uppercase mb-3 small fw-bold text-center">Contactanos</h5>
                <ul class="list-unstyled text-center">
                    <li class="mb-2">
                        <a href="tel:+543795372819" class="footer-link text-decoration-none">
                            <i class="bi bi-telephone me-2 texto-rojo"></i> 379 537-2819
                        </a>
                    </li>
                    <li class="mb-2">
                        <a class="footer-link text-decoration-none" href="mailto:info@sw-store.com">
                            <i class="bi bi-envelope me-2 texto-rojo"></i> info@sw-store.com
                        </a>
                    </li>
                    <li>
                        <a class="footer-link text-decoration-none" href="https://maps.app.goo.gl/yhESDqhKsFdvNLtk6">
                            <i class="bi bi-geo-alt me-2 texto-rojo"></i> 9 de Julio 1449, Corrientes
                        </a>
                    </li>
                </ul>
                <div class="d-flex justify-content-center gap-3 mt-2">
                    <a href="https://wa.me/5493795372819" class="footer-link fs-5 social-icon-hover" title="Whatsapp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://instagram.com" class="footer-link fs-5 social-icon-hover" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://facebook.com" class="footer-link fs-5 social-icon-hover" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://tiktok.com" class="footer-link fs-5 social-icon-hover" title="TikTok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
        </div>

        {{-- MOBILE: Accordion --}}
        <div class="accordion accordion-footer d-md-none mb-4" id="footerAccordion">

            {{-- Sección: Categorías --}}
            <div class="accordion-item footer-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button footer-accordion-btn collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#footerCategorias" aria-expanded="false">
                        <i class="bi bi-grid me-2 texto-rojo"></i> Categorías
                    </button>
                </h2>
                <div id="footerCategorias" class="accordion-collapse collapse" data-bs-parent="#footerAccordion">
                    <div class="accordion-body footer-accordion-body">
                        <ul class="list-unstyled text-center mb-0">
                            @foreach ($categorias as $categoria)
                                <li class="mb-2">
                                    <a class="footer-link text-decoration-none" href="{{ route('catalog', $categoria->id) }}">
                                        {{ $categoria->display_title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sección: Navegación --}}
            <div class="accordion-item footer-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button footer-accordion-btn collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#footerNavegacion" aria-expanded="false">
                        <i class="bi bi-compass me-2 texto-rojo"></i> Navegación
                    </button>
                </h2>
                <div id="footerNavegacion" class="accordion-collapse collapse" data-bs-parent="#footerAccordion">
                    <div class="accordion-body footer-accordion-body">
                        <ul class="list-unstyled text-center mb-0">
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('home') }}">Principal</a></li>
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('about') }}">Quiénes Somos</a></li>
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('marketing') }}">Comercialización</a></li>
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('contact') }}">Contacto</a></li>
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('terms') }}">Términos de Uso</a></li>
                            <li class="mb-2"><a class="footer-link text-decoration-none" href="{{ route('catalog') }}">Nuestro Catálogo</a></li>
                            <li><a class="footer-link text-decoration-none" href="{{ route('queries') }}">Consultas</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sección: Contacto --}}
            <div class="accordion-item footer-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button footer-accordion-btn collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#footerContacto" aria-expanded="false">
                        <i class="bi bi-envelope me-2 texto-rojo"></i> Contactanos
                    </button>
                </h2>
                <div id="footerContacto" class="accordion-collapse collapse" data-bs-parent="#footerAccordion">
                    <div class="accordion-body footer-accordion-body">
                        <ul class="list-unstyled text-center mb-0">
                            <li class="mb-2">
                                <a href="tel:+543795372819" class="footer-link text-decoration-none">
                                    <i class="bi bi-telephone me-2 texto-rojo"></i> 379 537-2819
                                </a>
                            </li>
                            <li class="mb-2">
                                <a class="footer-link text-decoration-none" href="mailto:info@sw-store.com">
                                    <i class="bi bi-envelope me-2 texto-rojo"></i> info@sw-store.com
                                </a>
                            </li>
                            <li class="mb-3">
                                <a class="footer-link text-decoration-none" href="https://maps.app.goo.gl/yhESDqhKsFdvNLtk6">
                                    <i class="bi bi-geo-alt me-2 texto-rojo"></i> 9 de Julio 1449, Corrientes
                                </a>
                            </li>
                        </ul>
                        {{-- Redes sociales dentro del accordion de contacto --}}
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://wa.me/5493795372819" class="footer-link fs-5 social-icon-hover" title="Whatsapp"><i class="bi bi-whatsapp"></i></a>
                            <a href="https://instagram.com" class="footer-link fs-5 social-icon-hover" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="https://facebook.com" class="footer-link fs-5 social-icon-hover" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://tiktok.com" class="footer-link fs-5 social-icon-hover" title="TikTok"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Copyright — siempre visible --}}
        <div class="row border-top border-muted-custom py-4 mt-2">
            <div class="col text-center">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-1">
                    <span class="text-muted-custom small">© {{ date('Y') }} <strong>Soundwave Store</strong>.</span>
                    <span class="text-muted-custom small">Todos los derechos reservados.</span>
                </div>
            </div>
        </div>
    </div>
</footer>