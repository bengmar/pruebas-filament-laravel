<x-layouts.auth>
    <x-slot name="title">Iniciar Sesión</x-slot>

    <div class="auth-card">
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold color-adaptativo text-uppercase">Inicia sesión</h2>
            <p class="small text-muted-adaptativo">Ingresa tus credenciales para continuar</p>
        </div>

        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="emailLogin" class="form-label color-adaptativo">Correo electrónico</label>
                {{-- La clase form-control ya es adaptativa en tu styles.css --}}
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="emailLogin"
                    placeholder="ejemplo@correo.com" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="passwordLogin" class="form-label color-adaptativo mb-0">Contraseña</label>
                    <!-- Este codigo dejo oculto si se implementa mas adelante
                    <a href="{{ route('queries', ['motivo' => 'cuenta']) }}"
                        class="text-decoration-none small color-dorado-adaptativo">
                        ¿Olvidaste tu contraseña?
                    </a> -->
                </div>
                <div class="position-relative d-flex align-items-center">
                    <input type="password" class="form-control pe-5" name="password" id="passwordLogin"
                        placeholder="********" required>

                    <button type="button" id="togglePassword"
                        class="btn position-absolute end-0 me-2 border-0 p-0 shadow-none"
                        style="background: none; color: var(--text-muted-adaptativo); height: 100%; z-index: 5;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-grid mt-4">
                {{-- Usamos btn-brand que ya tiene el estilo dorado adaptativo --}}
                <button type="submit" class="btn-brand py-2 fw-bold text-uppercase">
                    Ingresar
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('signup.create') }}" class="text-decoration-none small color-dorado-adaptativo fw-bold">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>

        <div class="mt-4 pt-3 border-top border-ui-adaptativa text-center">
            {{-- Usamos btn-volver-auth que definimos para el CSS migrado --}}
            <a href="{{ route('home') }}" class="btn-volver-auth w-100">
                <i class="bi bi-house-door me-2"></i>Ir al inicio
            </a>
        </div>
    </div>
    <script src="{{ asset('js/show-pass.js') }}"></script>
</x-layouts.auth>
