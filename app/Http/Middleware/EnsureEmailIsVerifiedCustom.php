<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedCustom
{
    /**
     * Rutas permitidas para un usuario autenticado pero con correo aún sin verificar.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'verification.notice',
        'verification.verify',
        'verification.send',
        'verification.update-email',
        'logout',
    ];

    /**
     * Maneja la solicitud entrante.
     *
     * Si el usuario está autenticado pero no ha verificado su correo,
     * no se le permite acceder a ninguna ruta de la plataforma excepto
     * la pantalla de aviso de verificación, reenvío, actualización de correo o logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Los usuarios invitados pueden navegar por las rutas públicas
        if (! $user) {
            return $next($request);
        }

        // Si el usuario implementa verificación y aún no ha confirmado su correo
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            // Permitir exclusivamente las rutas de verificación y cierre de sesión
            if ($request->routeIs($this->except)) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Debes verificar tu dirección de correo electrónico antes de acceder a la plataforma.',
                ], 403);
            }

            return redirect()->route('verification.notice')
                ->with('warning', 'Debes verificar tu correo electrónico antes de acceder a las secciones del sistema.');
        }

        return $next($request);
    }
}
