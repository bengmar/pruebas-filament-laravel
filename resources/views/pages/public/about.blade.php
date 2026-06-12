<x-layouts.layout>
    <x-slot name="title">Acerca De</x-slot>

    <div class="container p-2 p-md-4 px-lg-5">
        {{-- Quitamos text-light para que herede var(--color-texto) --}}
        <div class="pages-decoration my-4 px-3 px-md-5 pb-5">

            <h2 class="text-center border-bottom border-ui-adaptativa py-4 mb-5 color-adaptativo">Quiénes Somos</h2>

            <div class="row align-items-center mb-5">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('images/empresa.webp') }}" alt="Equipo Soundwave Store"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
                <div class="col-md-6">
                    <p class="color-adaptativo">En <strong class="texto-rojo text-uppercase">Soundwave Store</strong> creemos que la música no es
                        solo sonido: es emoción, identidad y conexión. Nacimos con la misión de acercar instrumentos de
                        calidad a quienes buscan expresarse, crear y vivir la experiencia musical en todas sus formas.
                    </p>
                    <p class="color-adaptativo">Somos más que una tienda en línea: somos un espacio para artistas, estudiantes y apasionados de
                        la música que desean encontrar el instrumento perfecto para su viaje creativo.</p>
                    <p class="color-adaptativo">Nuestro compromiso es acompañarte desde el primer acorde hasta el escenario, brindándote
                        asesoramiento y confianza.</p>
                </div>
            </div>

            {{-- Misión --}}
            <div class="row align-items-center mb-5 py-4 border-top border-ui-adaptativa">
                <div class="col-md-6 order-2 order-md-1">
                    <h3 class="mb-3 color-adaptativo">
                        <i class="bi bi-bullseye me-2" style="color:var(--color-seis);"></i>Misión
                    </h3>
                    <p class="color-adaptativo">Nuestra misión es acercar instrumentos de calidad y asesoramiento especializado a todas las
                        personas que viven la música como una forma de expresión e identidad.</p>
                </div>
                <div class="col-md-6 order-1 order-md-2 mb-4 mb-md-0">
                    <img src="{{ asset('images/mision.webp') }}" alt="Misión Soundwave Store"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
            </div>

            {{-- Visión --}}
            <div class="row align-items-center mb-5 py-4 border-top border-ui-adaptativa">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('images/vision.webp') }}" alt="Visión Soundwave Store"
                        class="img-fluid rounded shadow border-ui-adaptativa">
                </div>
                <div class="col-md-6">
                    <h3 class="mb-3 color-adaptativo">
                        <i class="bi bi-eye-fill me-2" style="color:var(--color-dos);"></i>Visión
                    </h3>
                    <p class="color-adaptativo">Soñamos con un mundo donde la música sea el puente que conecte a las personas. Nuestra visión es
                        convertirnos en el referente regional del comercio musical.</p>
                </div>
            </div>

            {{-- Equipo --}}
            <div class="pt-4 border-top border-ui-adaptativa">
                <h2 class="text-center mb-5 color-adaptativo text-uppercase">Personas que inspiran nuestra música</h2>
                <div class="row justify-content-center">
                    {{-- Usamos la clase 'team-card' para manejar los fondos dinámicos --}}
                    @php
                        $equipo = [
                            ['nombre' => 'Juan Pérez', 'rol' => 'Fundador y Director', 'img' => 'Fundador.webp', 'desc' => 'Juan dio vida a Soundwave Store para crear un espacio de inspiración.'],
                            ['nombre' => 'María López', 'rol' => 'Asesora Musical', 'img' => 'asesora.webp', 'desc' => 'Músico y docente, aporta su experiencia para guiar a cada cliente.'],
                            ['nombre' => 'Carlos Gómez', 'rol' => 'Ventas', 'img' => 'responsable-ventas.webp', 'desc' => 'Se encarga de que cada compra sea fluida y confiable.']
                        ];
                    @endphp

                    @foreach($equipo as $persona)
                    <div class="col-md-4 mb-4">
                        <div class="card team-card h-100 shadow-sm text-center p-3">
                            <img src="{{ asset('images/' . $persona['img']) }}"
                                class="rounded-circle mx-auto mb-3 team-img" alt="{{ $persona['nombre'] }}">
                            <h5 class="card-title texto-rojo">{{ $persona['nombre'] }}</h5>
                            <small class="text-muted-adaptativo text-uppercase mb-3 d-block">{{ $persona['rol'] }}</small>
                            <p class="card-text small color-adaptativo">{{ $persona['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout>
