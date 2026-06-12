@props(['card'])

<div class="card product-card h-100 m-1">
    {{-- Badge de descuento --}}
    @if ($card->on_sale)
        <div class="badge-producto" style="z-index: 3;">
            {{ $card->discount }}% OFF
        </div>
    @endif

    {{-- Imagen --}}
    <img src="{{ asset('storage/' . $card->image_1) }}" class="card-img-top" alt="Imagen de {{ $card->title }}" loading="lazy">

    <div class="card-body">
        <h5 class="card-title">{{ $card->title }}</h5>
        {{-- Cambié card-subtitle para que use la variable de texto muted del CSS --}}
        <p class="card-subtitle">{{ $card->subtitle }}</p>

        <div class="mt-auto">
            <div class="price-container">
                @if ($card->on_sale)
                    <div class="d-flex align-items-baseline gap-2">
                        <p class="precio-descuento mb-0">
                            ${{ number_format($card->final_price, 2, ',', '.') }}
                        </p>
                        <span class="descuento-tag">{{ $card->discount }}% OFF</span>
                    </div>
                    <p class="precio-original mb-0">
                        ${{ number_format($card->price, 2, ',', '.') }}
                    </p>
                @else
                    <div class="d-flex align-items-baseline">
                        <p class="precio mb-0">
                            ${{ number_format($card->price, 2, ',', '.') }}
                        </p>
                    </div>
                @endif
            </div>

            <p class="cuotas">
                {{ $card->installments }} x ${{ number_format($card->installment_price, 2, ',', '.') }}
                <span>sin interés</span>
            </p>

            <p class="envio"><i class="bi bi-truck"></i> Envío gratis</p>

            {{-- Stretched link --}}
            <a href="{{ route('product-details', ['id' => $card->id ]) }}" class="stretched-link"></a>

            {{-- FORMULARIO ADAPTADO PARA AGREGAR AL CARRITO --}}
            <form action="{{ route('cart.add') }}" method="POST" class="position-relative form-agregar-carrito" style="z-index: 4;">
                @csrf
                {{-- Enviamos los campos que el CartController espera validar --}}
                <input type="hidden" name="product_id" value="{{ $card->id }}">
                <input type="hidden" name="quantity" value="1">

                {{-- Botón cambiado a type="submit" --}}
                <button type="submit" class="btn-agregar w-100">
                    Añadir al carrito
                </button>
            </form>
        </div>
    </div>
</div>
