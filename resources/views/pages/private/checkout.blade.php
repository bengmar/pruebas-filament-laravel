<x-layouts.layout>
    <x-slot name='title'>Finalizar Compra</x-slot>
    <div class="container py-5">
        <div class="mb-4 animate__animated animate__fadeIn">
            <a href="{{ route('catalog') }}" class="checkout-back-link">
                <i class="bi bi-arrow-left-circle-fill me-2 fs-5"></i>
                <span>Volver al Catálogo</span>
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-7 col-lg-8 order-2 order-md-1">
                <div class="checkout-section-card shadow-sm p-4">
                    <h4 class="checkout-title color-adaptativo mb-4">Datos de Entrega</h4>

                    <form action="{{ route('checkout.store') }}" method="POST" id="form-checkout"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">

                            {{-- Nombre --}}
                            <div class="col-sm-6">
                                <label class="form-label color-adaptativo">Nombre</label>
                                <input type="text" name="customer_name"
                                    class="form-control checkout-input @error('customer_name') is-invalid @enderror"
                                    placeholder="Ej: Jonathan"
                                    value="{{ old('customer_name', auth()->user()->first_name ?? '') }}" required>
                                <div class="invalid-feedback">
                                    {{ $errors->first('customer_name') ?? 'El nombre es obligatorio.' }}
                                </div>
                            </div>

                            {{-- Apellido --}}
                            <div class="col-sm-6">
                                <label class="form-label color-adaptativo">Apellido</label>
                                <input type="text" name="customer_lastname"
                                    class="form-control checkout-input @error('customer_lastname') is-invalid @enderror"
                                    placeholder="Ej: Aguilar"
                                    value="{{ old('customer_lastname', auth()->user()->last_name ?? '') }}" required>

                                <div class="invalid-feedback">
                                    {{ $errors->first('customer_lastname') ?? 'El apellido es obligatorio.' }}
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-12">
                                <label class="form-label color-adaptativo">Email</label>
                                <input type="email" name="customer_email"
                                    class="form-control checkout-input @error('customer_email') is-invalid @enderror"
                                    placeholder="nombre@ejemplo.com"
                                    value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>

                                <div class="invalid-feedback">
                                    {{ $errors->first('customer_email') ?? 'El email es obligatorio y debe ser válido.' }}
                                </div>
                            </div>

                            {{-- SELECCIÓN DE DIRECCIÓN --}}
                            <div class="col-12">
                                <label class="form-label color-adaptativo">Mis Direcciones Guardadas</label>
                                <select name="user_address_id" id="select-direccion-checkout"
                                    class="form-select checkout-input" onchange="evaluarDireccionNueva(this)" required>
                                    @foreach ($direcciones as $dir)
                                        <option value="{{ $dir->id }}" data-street="{{ $dir->street }}"
                                            data-postal="{{ $dir->postal_code }}" data-city="{{ $dir->city_id }}"
                                            {{ old('user_address_id', $dir->is_default ? $dir->id : '') == $dir->id ? 'selected' : '' }}>
                                            {{ $dir->alias }} ({{ $dir->street }}, {{ $dir->city->name }})
                                            {{ $dir->is_default ? '— [Predeterminada]' : '' }}
                                        </option>
                                    @endforeach
                                    <option value="nueva_direccion"
                                        {{ old('user_address_id', $direcciones->isEmpty() ? 'nueva_direccion' : '') == 'nueva_direccion' ? 'selected' : '' }}>
                                        ➕ Usar una nueva dirección de envío...
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_address_id') ?? 'Seleccioná una dirección.' }}
                                </div>
                            </div>

                            {{-- BLOQUE OCULTO PARA NUEVA DIRECCIÓN --}}
                            <div id="bloque-nueva-direccion"
                                class="{{ $direcciones->isNotEmpty() && old('user_address_id', 'guardada') !== 'nueva_direccion' ? 'd-none' : '' }} mt-3 pt-3">
                                <div class="p-3 bg-superficie-adaptativa rounded border border-ui-adaptativa row g-3">
                                    <h6 class="color-dorado-adaptativo fw-bold mb-0">Registrar nueva dirección de
                                        entrega</h6>

                                    <div class="col-12">
                                        <label class="form-label color-adaptativo small">Calle, Número,
                                            Piso/Depto</label>
                                        <input type="text" name="delivery_street" id="input-nueva-calle"
                                            class="form-control checkout-input form-control-sm @error('delivery_street') is-invalid @enderror"
                                            placeholder="Ej: Av. Tres de Abril 1234, Piso 2 B"
                                            value="{{ old('delivery_street') }}">

                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_street') ?? 'La calle es obligatoria.' }}
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label color-adaptativo small">Código Postal</label>
                                        <input type="text" name="delivery_postal_code" id="input-nuevo-cp"
                                            class="form-control checkout-input form-control-sm @error('delivery_postal_code') is-invalid @enderror"
                                            placeholder="Ej: 3400" value="{{ old('delivery_postal_code') }}">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_postal_code') ?? 'El código postal es obligatorio.' }}
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label color-adaptativo small">Alias de la dirección</label>
                                        <input type="text" name="delivery_alias" id="input-nuevo-alias"
                                            class="form-control checkout-input form-control-sm @error('delivery_alias') is-invalid @enderror"
                                            placeholder="Ej: Casa Nueva, Depto Facu"
                                            value="{{ old('delivery_alias') }}">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_alias') ?? 'El alias de la dirección es obligatorio.' }}
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label color-adaptativo small">Provincia</label>
                                        <select id="select-provincia-checkout" class="form-select form-select-sm"
                                            onchange="cargarCiudadesCheckout(this)">
                                            <option value="" disabled selected>Selecciona Provincia...</option>
                                            @foreach ($provincias as $provincia)
                                                <option value="{{ $provincia->id }}">{{ $provincia->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label color-adaptativo small">Ciudad</label>
                                        <input type="hidden" name="delivery_city_id" id="hidden-city-id"
                                            value="{{ old('delivery_city_id') }}">
                                        <select id="select-ciudad-checkout"
                                            class="form-select form-select-sm @error('delivery_city_id') is-invalid @enderror"
                                            onchange="document.getElementById('hidden-city-id').value = this.value"
                                            disabled>
                                            <option value="" disabled selected>Selecciona Ciudad...</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('delivery_city_id') ?? 'Seleccioná una ciudad.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="checkout-separator">

                        <h4 class="checkout-title color-adaptativo mb-3">Método de Pago</h4>
                        @error('paymentMethod')
                            <div class="alert alert-danger py-2 small mb-2">{{ $message }}</div>
                        @enderror
                        <div class="payment-options d-flex flex-column gap-2">
                            <div class="checkout-payment-option rounded border">
                                <label class="form-check d-flex align-items-center p-3 mb-0 w-100 cursor-pointer"
                                    for="credit">
                                    <input id="credit" name="paymentMethod" type="radio"
                                        class="form-check-input me-3 mt-0" value="credit"
                                        {{ old('paymentMethod') == 'credit' ? 'checked' : '' }} required>
                                    <span class="form-check-label color-adaptativo fw-bold">
                                        Tarjeta de Crédito / Débito
                                    </span>
                                </label>
                            </div>

                            <div class="checkout-payment-option rounded border">
                                <label class="form-check d-flex align-items-center p-3 mb-0 w-100 cursor-pointer"
                                    for="transfer_bank">
                                    <input id="transfer_bank" name="paymentMethod" type="radio"
                                        class="form-check-input me-3 mt-0" value="transfer_bank"
                                        {{ old('paymentMethod') == 'transfer_bank' ? 'checked' : '' }} required>
                                    <span class="form-check-label color-adaptativo fw-bold">
                                        Transferencia Bancaria
                                    </span>
                                </label>
                            </div>

                            <div class="checkout-payment-option rounded border">
                                <label class="form-check d-flex align-items-center p-3 mb-0 w-100 cursor-pointer"
                                    for="transfer_mp">
                                    <input id="transfer_mp" name="paymentMethod" type="radio"
                                        class="form-check-input me-3 mt-0" value="transfer_mp"
                                        {{ old('paymentMethod') == 'transfer_mp' ? 'checked' : '' }} required>
                                    <span class="form-check-label color-adaptativo fw-bold">
                                        Mercado Pago
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div id="payment-error" class="text-danger small mt-2 d-none" style="font-size: 0.85rem;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> Seleccioná un método de pago para
                            continuar.
                        </div>

                        <div class="d-grid gap-3 mt-4">
                            <button class="btn-confirm-order py-3 fw-bold text-uppercase" type="submit">
                                Confirmar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Resumen lateral mejorado --}}
            <div class="col-md-5 col-lg-4 order-1 order-md-2">
                <div class="card checkout-summary-card shadow-sm border-0">
                    <div class="summary-header p-4">
                        <h5 class="d-flex justify-content-between align-items-center mb-0 fw-bold color-adaptativo">
                            <span>RESUMEN</span>
                            <span class="badge rounded-pill bg-adaptativo-badge px-3 py-2">
                                {{ $cart->items->sum('quantity') }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        @php
                            $subtotal = 0;
                        @endphp

                        {{-- Listado de productos reales --}}
                        <div class="productos-checkout-lista mb-3" style="max-height: 280px; overflow-y: auto;">
                            @foreach ($cart->items as $item)
                                @php
                                    // =========================================================================
                                    // BLINDAJE CON OPERADOR NULL-SAFE (?->) Y RESPALDO (??)
                                    // =========================================================================
                                    $precioUnitario = $item->product?->final_price ?? 0;
                                    $itemTotal = $precioUnitario * $item->quantity;
                                    $subtotal += $itemTotal;
                                @endphp

                                <div class="product-item d-flex align-items-center justify-content-between mb-3">
                                    <div class="product-info grow me-2">
                                        <h6 class="product-name color-adaptativo mb-0 fw-bold text-uppercase small text-truncate"
                                            style="max-width: 160px;">
                                            {{ $item->product?->title ?? 'Producto no disponible' }}
                                        </h6>
                                        <small class="text-muted-adaptativo">
                                            Cant: {{ $item->quantity }} x
                                            ${{ number_format($precioUnitario, 0, ',', '.') }}
                                        </small>
                                    </div>
                                    <span class="product-price color-adaptativo fw-bold small flex-shrink-0">
                                        ${{ number_format($itemTotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted-adaptativo">Envío</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>

                        <div class="summary-total-section pt-3 mt-3 border-top border-ui-adaptativa">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5 color-adaptativo">TOTAL</span>
                                <strong class="total-amount fs-4 color-dorado-adaptativo">
                                    ${{ number_format($subtotal, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/checkout-direcciones.js') }}"></script>
</x-layouts.layout>
