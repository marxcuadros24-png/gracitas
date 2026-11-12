<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('warning', 'Debes iniciar sesión para acceder a esta sección.');
        }

        $user = auth()->user();

        if (! $user->activo) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Tu cuenta se encuentra desactivada. Contacta al administrador.');
        }

        if (! in_array($user->rol, $roles)) {
            abort(403, 'No tienes permisos para acceder a este recurso.');
        }

        return $next($request);
    }
}
