<x-layouts.auth>
    <x-slot name="title">Registro</x-slot>

    <div class="auth-card">
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold color-adaptativo text-uppercase">Crea tu cuenta</h2>
            <p class="small text-muted-adaptativo">Únete a la comunidad de Soundwave Store</p>
        </div>

        <form action="{{ route('signup.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="nameSignup" class="form-label color-adaptativo">Apellido/s</label>
                    <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name"
                        id="surnameSignup" placeholder="Pérez" required>
                    @error('last_name')
                        <small class="text-danger"></span>{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="nameSignup" class="form-label color-adaptativo">Nombre/s</label>
                    <input type="text" class="form-control" value="{{ old('first_name') }}" name="first_name"
                        id="nameSignup" placeholder="Juan Carlos" required>
                    @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="emailSignup" class="form-label color-adaptativo">Correo electrónico</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="emailSignup"
                    placeholder="ejemplo@correo.com" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="passwordSignup" class="form-label color-adaptativo">Contraseña</label>
                    <div class="position-relative d-flex align-items-center">
                        <input type="password" class="form-control pe-5" name="password" id="passwordSignup"
                            placeholder="********" required>
                        <button type="button" id="togglePasswordSignup"
                            class="btn position-absolute end-0 me-2 border-0 p-0 shadow-none"
                            style="background: none; color: var(--text-muted-adaptativo); height: 100%; z-index: 5;">
                            <i class="bi bi-eye" id="eyeIconSignup"></i>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="passwordConfirmSignup" class="form-label color-adaptativo">Confirmar</label>
                    <div class="position-relative d-flex align-items-center">
                        <input type="password" class="form-control pe-5" name="password_confirmation"
                            id="passwordConfirmSignup" placeholder="********" required>
                        <button type="button" id="togglePasswordConfirm"
                            class="btn position-absolute end-0 me-2 border-0 p-0 shadow-none"
                            style="background: none; color: var(--text-muted-adaptativo); height: 100%; z-index: 5;">
                            <i class="bi bi-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn-brand py-2 fw-bold text-uppercase">
                    Crear cuenta
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-decoration-none small color-dorado-adaptativo fw-bold">
                ¿Ya tienes cuenta? Inicia sesión
            </a>
        </div>

        <div class="mt-4 pt-3 border-top border-ui-adaptativa text-center">
            <a href="{{ route('home') }}" class="btn-volver-auth w-100">
                <i class="bi bi-house-door me-2"></i>Ir al inicio
            </a>
        </div>
    </div>
    <script src="{{ asset('js/show-pass-signup.js') }}"></script>
</x-layouts.auth>
