<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configurar los limitadores de tasa (Rate Limiters) de la aplicación.
     */
    protected function configureRateLimiting(): void
    {
        // Limitador general para API (60 req/min por usuario autenticado o IP)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Demasiadas solicitudes a la API. Por favor espere antes de reintentar.',
                        'retry_after_seconds' => (int) ($headers['Retry-After'] ?? 60),
                    ], 429, $headers);
                });
        });

        // Limitador estricto para autenticación en API (login y register: 5 req/min)
        RateLimiter::for('api.auth', function (Request $request) {
            $email = (string) $request->input('email', '');
            $key = Str::transliterate(Str::lower($email).'|'.$request->ip());

            return Limit::perMinute(5)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Demasiados intentos de autenticación en la API. Por favor intente más tarde.',
                        'retry_after_seconds' => (int) ($headers['Retry-After'] ?? 60),
                    ], 429, $headers);
                });
        });

        // Limitador para inicio de sesión web (5 intentos/min por email e IP)
        RateLimiter::for('web.login', function (Request $request) {
            $email = (string) $request->input('email', '');
            $key = Str::transliterate(Str::lower($email).'|'.$request->ip());

            return Limit::perMinute(5)->by($key);
        });

        // Limitador para registro web (5 intentos/min por IP)
        RateLimiter::for('web.register', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Limitador para recuperación de contraseñas (3 intentos/min por email o IP)
        RateLimiter::for('password.reset', function (Request $request) {
            $email = (string) $request->input('email', '');
            $key = Str::transliterate(Str::lower($email).'|'.$request->ip());

            return Limit::perMinute(3)->by($key);
        });

        // Limitador para consulta de disponibilidad en vivo (60 req/min)
        RateLimiter::for('disponibilidad', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
