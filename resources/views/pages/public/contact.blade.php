<x-layouts.layout>
    <x-slot name="title">Contacto</x-slot>
    <div class="container p-2 p-md-4 px-lg-5">
        {{-- Quitamos text-light para que use var(--color-texto) --}}
        <div class="pages-decoration my-4 px-3 px-md-5 pb-4">
            <div>
                <h2 class="text-center border-bottom border-ui-adaptativa py-4 color-adaptativo">Contacto</h2>
                <p class="px-2 px-md-4 color-adaptativo">
                    En Soundwave Store valoramos la comunicación directa y transparente con nuestros clientes y socios
                    comerciales. Ponemos a tu disposición diferentes canales:
                </p>
                <div class="px-2 px-md-4 color-adaptativo">
                    <ul class="list-custom">
                        <li><strong>Atención al cliente:</strong> consultas sobre productos y compras.</li>
                        <li><strong>Área comercial:</strong> cotizaciones y acuerdos.</li>
                        <li><strong>Soporte postventa:</strong> garantías y asistencia técnica.</li>
                    </ul>
                </div>
            </div>

            <div class="row d-flex justify-content-center border-top border-ui-adaptativa mt-4 g-4">
                <div class="col-12 col-md-7">
                    <h3 class="text-center border-bottom border-ui-adaptativa py-4 color-adaptativo">Datos de Contacto</h3>
                    <ul class="list-unstyled text-center text-md-start ps-md-3">
                        <li class="mb-3">
                            <i class="bi bi-envelope me-2 texto-rojo"></i>
                            <strong class="color-adaptativo">Correo:</strong>
                            <a href="mailto:info@sw-store.com"
                                class="color-adaptativo text-decoration-none ms-1">info@sw-store.com</a>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-telephone me-2 texto-rojo"></i>
                            <strong class="color-adaptativo">Teléfono:</strong>
                            <a href="tel:+543795372819" class="color-adaptativo text-decoration-none ms-1">(+54) 379-5372819</a>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-geo-alt me-2 texto-rojo"></i>
                            <strong class="color-adaptativo">Dirección:</strong>
                            <a href="https://maps.app.goo.gl/yhESDqhKsFdvNLtk6" class="color-adaptativo text-decoration-none ms-1">
                                9 de Julio 1449 (Corrientes, Argentina)</a>
                        </li>

                        {{-- Redes Sociales con estilo adaptativo --}}
                        <li class="mb-2 pt-2">
                            <div class="d-flex justify-content-center justify-content-md-start gap-3">
                                <a href="https://wa.me/5493795372819" class="social-btn-contact" title="Whatsapp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="https://instagram.com" class="social-btn-contact" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="https://facebook.com" class="social-btn-contact" title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://tiktok.com" class="social-btn-contact" title="TikTok">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            </div>
                        </li>
                    </ul>

                    <div class="text-center text-md-start ps-md-3 mt-4">
                        <p class="small mb-2 text-muted-adaptativo">¿Tienes una duda específica?</p>
                        <a href="{{ route('queries') }}" class="btn-outline-adaptativo d-inline-block px-4 py-2 text-decoration-none small">
                            <i class="bi bi-chat-left-text me-2"></i>Ir al Formulario de Consultas
                        </a>
                    </div>

                    <h3 class="text-center border-bottom border-ui-adaptativa py-4 mt-2 color-adaptativo">Horarios de Atención</h3>
                    <div class="text-center text-md-start ps-md-3 color-adaptativo">
                        <p class="mb-1">Lunes a viernes de 9:00 a 18:00 hs.</p>
                        <p>Sábados de 9:00 a 13:00 hs.</p>
                    </div>
                </div>

                {{-- Mapa con borde adaptativo --}}
                <div class="col-12 col-md-5 py-4">
                    <div class="ratio ratio-16x9 h-100 map-container shadow-sm">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1770.0403480440668!2d-58.832390000000004!3d-27.466747!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94456ca6d24ec0c9%3A0xb92ce3fedb0d7729!2s%C3%81rea%20Graduados%20-%20Ciencias%20Exactas%20y%20Naturales%20y%20Agrimensura%20-%20UNNE!5e0!3m2!1ses!2sar!4v1776349216306!5m2!1ses!2sar"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>
