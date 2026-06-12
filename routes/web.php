<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS / ACCESIBLES A TODOS ---

// Accesos sencillos
Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/comercializacion', 'marketing')->name('marketing');
    Route::get('/terminos', 'terms')->name('terms');
    Route::get('/acerca-de', 'about')->name('about');
    Route::get('/contacto', 'contact')->name('contact');
});

// Manejo de catálogo (Los productos son manejados por Filament)
Route::controller(CatalogController::class)->group(function () {
    Route::get('/catalogo/{categoria?}', 'index')->name('catalog');
    Route::get('/producto-detalles/{id}', 'details')->name('product-details');
});

// Formulario de Consultas
Route::controller(QueriesController::class)->group(function () {
    Route::get('/consultas', 'create')->name('queries');
    Route::post('/enviar-consulta', 'store')->name('queries.send');
});

// Búsquedas
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Autenticación - Solo para invitados
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Registro
    Route::get('/signup', 'create')->name('signup.create');
    Route::post('/signup', 'store')->name('signup.store');
    // Login
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'authenticate')->name('login.authenticate');
});


//RUTAS PROTEGIDAS - Inicio de sesión requerido -
Route::middleware('auth')->group(function () {

    // Autenticación general -admin y clientes-
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // EXCLUSIVO PARA CLIENTES (redirige a administradores). Funciona logueado y con ID de rol != 1
    Route::middleware('no.admin')->group(function () {

        //Panel de usuario y de actualización de datos(el de admin lo maneja filament)
        Route::controller(UserController::class)->group(function () {
            Route::get('/panel-usuario', 'index')->name('panel-usuario');
            Route::put('/panel-usuario', 'update')->name('panel-usuario.update');
            Route::delete('/panel-usuario/eliminar', [UserController::class, 'destroy'])->name('panel-usuario.destroy');
        });

        //Rutas del carrito
        Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
            Route::get('/', 'index')->name('list');
            Route::post('/add', 'add')->name('add');
            Route::put('/update/{itemId}', 'updateQuantity')->name('update');
            Route::delete('/remove/{itemId}', 'removeItem')->name('remove');
            Route::delete('/clear', 'clear')->name('clear');
        });

        //Checkout y pedidos
        Route::get('/panel-cliente', [MainController::class, 'userPanel']);
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        // Procesar el formulario de compra
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        // Ruta de éxito
        Route::get('/pedido-exitoso/{order}', [CheckoutController::class, 'success'])->name('orders.success');
        //Listado de compras realizadas
        Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('mis-pedidos');
    });
});



// --- endpoints / API INTERNA (Para peticiones asíncronas de JavaScript) ---
Route::get('/api/provincias/{province}/ciudades', [UserController::class, 'getCitiesByProvince'])->name('api.ciudades');

// 3. FALLBACK (404 personalizado)=
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
