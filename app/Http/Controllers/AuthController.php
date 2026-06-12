<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SweetAlert2\Laravel\Swal;

class AuthController extends Controller
{
    //FUNCIONES ASOCIADAS AL FORMULARIO DE REGISTRO Y CREACIÓN DE CUENTA
    public function create()
    {
        return view('auth.signup');
    }

    public function store(SignUpRequest $request)
    {

        $validated = $request->validated();

        // Aseguramos que el role_id sea 2 y la contraseña esté encriptada
        $validated['role_id'] = 2;
        $validated['password'] = Hash::make($validated['password']);

        // Creando el usuario
        $user = User::create($validated);

        //  Login automático
        Auth::login($user);

        /* Esto lo dejo aqui por si no convence
        
        Swal::success([
            'title' => '!Hecho!',
            'text' => '¡La cuenta ha sido creada'
        ]);
        return redirect()->route('login')->with('swal_success', 'Se ha creado la cuenta de usuario exitosamente');s
        */
 
        return redirect()->route('home')
            ->with('cart_success', '¡Cuenta creada e inicio de sesión automático!');
    }

    //FUNCIONES ASOCIADAS AL LOGIN
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Recuperando la ruta a la que el usuario intentaba ir antes de loguearse
            $intendedUrl = session()->get('url.intended');


            // Lógica para el Admin
            if ($user->role_id === 1) {
                // Si venía de una ruta de administración (contiene '/admin'), lo lleva hacia alla.
                // Si venía del Home o catálogo, lo dejamos en el Home.
                return redirect()->intended(route('home'));
            }

            // LÓGICA PARA EL CLIENTE
            if ($user->role_id === 2) {
                // Saneamiento: Si la URL previa contiene '/admin', un cliente NO puede ir ahí.(Se borra la 'intención para evitar 403)
                if ($intendedUrl && str_contains($intendedUrl, '/admin')) {
                    $request->session()->forget('url.intended');
                    return redirect()->route('home')
                        ->with('system_success', '¡Bienvenido! Has iniciado sesión correctamente.');
                }

                // Si la ruta era segura (ej: /checkout, /cart, o el Home), lo dejamos continuar
                return redirect()->intended(route('home'))
                    ->with('system_success', '¡Bienvenido! Has iniciado sesión correctamente.');
            }

            // Fallback general
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
    }

    public function logout(Request $request)
    {
        // 1. Cerramos la sesión en el "Guard" de Laravel
        Auth::logout();

        // 2. Invalidamos la sesión del usuario en el servidor
        $request->session()->invalidate();

        // 3. Regeneramos el token CSRF para la siguiente visita
        $request->session()->regenerateToken();

        // 4. Redirigimos a la página principal o al login
        return redirect()->route('home')->with('system_success', 'Sesión cerrada correctamente.');
    }
}
