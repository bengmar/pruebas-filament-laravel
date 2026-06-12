<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si está logueado y es admin, lo redirigimos a la ruta del panel de Filament o tu ruta admin
        if (Auth::check() && Auth::user()->role->name === 'admin') {
            // CLAVE: Si la petición espera un JSON o es AJAX, no redirigimos, tiramos 403 directo
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'ADMIN_RESTRICTED',
                    'message' => 'Los administradores no pueden realizar acciones de clientes.'
                ], 403);
            }

            // Si es una petición común de navegador, que siga haciendo el redirect que ya tenías
            return redirect('/admin')->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}
