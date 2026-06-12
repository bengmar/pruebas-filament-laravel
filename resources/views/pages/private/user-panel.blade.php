<x-layouts.layout>
    <x-slot name='title'>Mi Perfil — {{ auth()->user()->first_name }}</x-slot>

    <div class="container py-5" id="perfil-app" data-tiene-errores="{{ $errors->any() ? 'true' : 'false' }}">
        @if (session('success'))
            <div class="alert alert-success text-center mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <p class="text-center fw-bold mb-1">Hubo errores al guardar los cambios. Revisa los campos:</p>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">

                {{-- Cabecera de perfil --}}
                <div class="perfil-header d-flex align-items-center gap-3 mb-4">
                    <div class="perfil-avatar">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="perfil-nombre mb-0">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </h2>
                        <span class="perfil-email">{{ auth()->user()->email }}</span>
                    </div>
                </div>

                {{-- Modo VISTA: datos del usuario --}}
                <div id="modo-vista">
                    <div class="checkout-section-card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="checkout-title color-adaptativo mb-0">Mis datos</h5>
                            <button onclick="activarEdicion()" class="btn-editar">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </button>
                        </div>

                        <div class="perfil-campo">
                            <span class="perfil-label">Nombre</span>
                            <span class="perfil-valor">{{ auth()->user()->first_name }}</span>
                        </div>
                        <hr class="checkout-separator my-3">
                        <div class="perfil-campo">
                            <span class="perfil-label">Apellido</span>
                            <span class="perfil-valor">{{ auth()->user()->last_name }}</span>
                        </div>
                        <hr class="checkout-separator my-3">
                        <div class="perfil-campo">
                            <span class="perfil-label">Correo electrónico</span>
                            <span class="perfil-valor">{{ auth()->user()->email }}</span>
                        </div>
                        <hr class="checkout-separator my-3">
                        <div class="perfil-campo">
                            <span class="perfil-label">Contraseña</span>
                            <span class="perfil-valor text-muted-adaptativo">••••••••</span>
                        </div>
                        <hr class="checkout-separator my-3">
                        <div class="perfil-campo">
                            <span class="perfil-label">Teléfono</span>
                            <span class="perfil-valor">
                                {{ $user->profile?->phone ?? 'No registrado' }}
                            </span>
                        </div>
                        <hr class="checkout-separator my-3">
                        <h5 class="checkout-title color-adaptativo mb-3 mt-4">Mis direcciones</h5>
                        @if ($user->addresses->isNotEmpty())
                            @foreach ($user->addresses as $address)
                                <div class="perfil-campo mb-3">
                                    <span class="perfil-label">{{ $address->alias }}</span>
                                    <span class="perfil-valor">
                                        {{ $address->street }},
                                        {{ $address->city?->name }},
                                        {{ $address->city?->province?->name }}
                                        (CP {{ $address->postal_code }})
                                    </span>
                                    @if ($address->is_default)
                                        <span class="badge bg-success ms-2">Predeterminada</span>
                                    @endif
                                </div>
                                <hr class="checkout-separator my-3">
                            @endforeach
                        @else
                            <div class="text-center text-muted-adaptativo p-3 border rounded">
                                No tienes direcciones registradas.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Modo EDICIÓN: formulario --}}
                <div id="modo-edicion" class="hidden">
                    <div class="checkout-section-card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="checkout-title color-adaptativo mb-0">Editar perfil</h5>
                            <button onclick="cancelarEdicion()" class="btn-cancelar">
                                <i class="bi bi-x-lg me-1"></i> Cancelar
                            </button>
                        </div>

                        <form action="{{ route('panel-usuario.update') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label color-adaptativo">Nombre</label>
                                    <input type="text" name="first_name"
                                        class="form-control checkout-input @error('first_name') is-invalid @enderror"
                                        placeholder="Ej: Jonathan"
                                        value="{{ old('first_name', auth()->user()->first_name) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label color-adaptativo">Apellido</label>
                                    <input type="text" name="last_name"
                                        class="form-control checkout-input @error('last_name') is-invalid @enderror"
                                        placeholder="Ej: Aguilar"
                                        value="{{ old('last_name', auth()->user()->last_name) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label color-adaptativo">Correo electrónico</label>
                                    <input type="email" name="email"
                                        class="form-control checkout-input @error('email') is-invalid @enderror"
                                        placeholder="nombre@ejemplo.com"
                                        value="{{ old('email', auth()->user()->email) }}" required>
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label color-adaptativo">Teléfono</label>
                                <input type="text" name="phone"
                                    class="form-control checkout-input @error('phone') is-invalid @enderror"
                                    placeholder="Ej: +54 379 1234567"
                                    value="{{ old('phone', $user->profile?->phone) }}">
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            </div>

                            <hr class="checkout-separator">

                            {{-- SECCIÓN SELECCIONABLE DE DIRECCIONES DINÁMICAS --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="checkout-title color-adaptativo mb-0">Mis Direcciones</h6>
                                <button type="button" class="btn-editar" onclick="agregarDireccion()">
                                    <i class="bi bi-plus-lg"></i> Añadir dirección
                                </button>
                            </div>

                            <div id="contenedor-direcciones">
                                {{-- Aquí se cargarán las direcciones mediante JS --}}
                            </div>

                            <hr class="checkout-separator">

                            <h6 class="checkout-title color-adaptativo mb-3" style="font-size: 0.85rem;">
                                Cambiar contraseña <span class="text-muted-adaptativo fw-normal">(opcional)</span>
                            </h6>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label color-adaptativo">Nueva contraseña</label>
                                    <input type="password" name="password"
                                        class="form-control checkout-input @error('password') is-invalid @enderror"
                                        placeholder="Dejar vacío para no cambiar">
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label color-adaptativo">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control checkout-input" placeholder="Repetir nueva contraseña">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <hr class="checkout-separator">

                            {{-- SECCIÓN ELIMINAR CUENTA --}}
                            <div class="card border-danger-subtle bg-danger-subtle bg-opacity-10 p-3 mb-4">
                                <h6 class="text-danger fw-bold mb-2">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> ¡ATENCIÓN!
                                </h6>
                                <p class="small text-muted-adaptativo mb-3">
                                    Una vez que elimines tu cuenta, no podrás acceder a tu panel. Tus datos se
                                    desactivarán de forma lógica de acuerdo con nuestras políticas. Deberás contactar
                                    con soporte para reestablecerla.
                                </p>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmarEliminarCuenta()">
                                        Eliminar mi cuenta
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn-confirm-order py-3 fw-bold text-uppercase">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                        {{-- SECCIÓN AGREGADA: FORMULARIO INDEPENDIENTE PARA SOFTDELETE --}}
                        <form id="form-eliminar-cuenta" action="{{ route('panel-usuario.destroy') }}" method="POST"
                            class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- TEMPLATE HTML OCULTO PARA JAVASCRIPT (Súper limpio) --}}
    <template id="template-direccion">
        <div class="card p-3 mb-3 posicion-direccion border-light shadow-sm" data-index="{index}">
            <input type="hidden" name="addresses[{index}][id]" value="{id}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-secondary small">Dirección #{numero}</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0"
                    onclick="eliminarDireccionElemento(this)">
                    <i class="bi bi-trash"></i> Quitar
                </button>
            </div>
            <div class="row g-2">
                <div class="col-sm-6">
                    <input type="text" name="addresses[{index}][alias]" class="form-control form-control-sm"
                        placeholder="Alias (Ej: Casa, Trabajo)" value="{alias}" required>
                    <div class="invalid-feedback">El alias es obligatorio.</div>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="addresses[{index}][postal_code]" class="form-control form-control-sm"
                        placeholder="Cód. Postal" value="{postal_code}" required>
                    <div class="invalid-feedback">El código postal es obligatorio.</div>
                </div>
                <div class="col-12">
                    <input type="text" name="addresses[{index}][street]" class="form-control form-control-sm"
                        placeholder="Calle, número, piso/depto" value="{street}" required>
                    <div class="invalid-feedback">La calle es obligatoria.</div>
                </div>
                {{-- SELECT DE PROVINCIAS --}}
                <div class="col-sm-6">
                    <select class="form-select form-select-sm select-provincia"
                        onchange="cargarCiudadesDinamico(this)" required>
                        <option value="" disabled selected>Selecciona Provincia...</option>
                        @foreach ($provincias as $provincia)
                            <option value="{{ $provincia->id }}">{{ $provincia->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Seleccioná una provincia.</div>
                </div>

                {{-- SELECT DE CIUDADES (Dependiente) --}}
                <div class="col-sm-6">
                    <select name="addresses[{index}][city_id]" class="form-select form-select-sm select-ciudad"
                        required disabled>
                        <option value="" disabled selected>Selecciona Ciudad...</option>
                    </select>
                    <div class="invalid-feedback">Seleccioná una ciudad.</div>
                </div>
                <div class="col-12 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="addresses[{index}][is_default]"
                            value="1" id="def-{index}" {checked}>
                        <label class="form-check-label small" for="def-{index}">Establecer como predeterminada</label>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        // Compartimos las direcciones del usuario autenticado de Laravel hacia Javascript de manera segura
        const direccionesIniciales = @json($user->addresses()->with('city.province')->get());

        //Script para la confirmación de la eliminación de la cuenta
        function confirmarEliminarCuenta() {
            const mensaje =
                "¿Estás completamente seguro de que deseas eliminar tu cuenta?\n\nEsta acción suspenderá tu acceso de forma inmediata.";

            if (confirm(mensaje)) {
                // Ejecuta el envío del formulario oculto si el usuario acepta
                document.getElementById('form-eliminar-cuenta').submit();
            }
        }
    </script>
    <script src="{{ asset('js/activar-editar.js') }}"></script>
</x-layouts.layout>
