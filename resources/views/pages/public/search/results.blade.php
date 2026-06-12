<x-layouts.layout>
    <x-slot name="title">Búsqueda</x-slot>
    <div class="container my-5">
        <h2 class="mb-3 text-muted">Resultados para: "{{ $query }}" 🔍 </h2>

        @if ($results->isEmpty())
            <div class=" text-center shadow-sm pages-decoration pb-4 my-4 px-3 px-md-5">
                <h3 class="border-bottom border-ui-adaptativa py-4 text-muted">¡Ups!</h3>
                <p class="text-muted py-3">No encontramos marcas,categorías o instrumentos que coincidan con tu búsqueda.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center px-2 gap-2 gap-sm-3 ">
                    <a href="{{ route('home') }}" class="btn special-btn w-sm-auto">Ir Al Inicio</a>
                    <a href="{{ route('catalog') }}" class="btn special-btn w-sm-auto">Ver Catálogo</a>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                @foreach ($results as $product)
                    <div class="col">
                        {{-- Llamamos al componente. Pasamos la variable $product a la prop 'card'. --}}
                        <x-card :card="$product" />
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper d-flex justify-content-center mt-5">
                {{ $results->appends(['query' => $query])->links() }}
            </div>
        @endif
    </div>
    {{-- Paginación {{ $results->links() }} --}}
</x-layouts.layout>
