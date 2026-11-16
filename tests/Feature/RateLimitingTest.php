<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clearResolvedInstances();
    }

    /**
     * Verifica que el limitador api.auth bloquee el 6to intento de login en la API.
     */
    public function test_api_auth_login_rate_limiting_blocks_after_five_attempts(): void
    {
        $payload = [
            'email' => 'atacante@ejemplo.com',
            'password' => 'clave-erronea',
        ];

        // 5 intentos permitidos retornan 401 (credenciales erróneas)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson(route('api.auth.login'), $payload);
            $this->assertEquals(401, $response->status());
        }

        // El 6to intento debe ser interceptado por el middleware y responder 429 Too Many Requests
        $blocked = $this->postJson(route('api.auth.login'), $payload);

        $blocked->assertStatus(429)
            ->assertHeader('Retry-After')
            ->assertJson([
                'success' => false,
                'message' => 'Demasiados intentos de autenticación en la API. Por favor intente más tarde.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'retry_after_seconds',
            ]);
    }

    /**
     * Verifica que el limitador api.auth bloquee peticiones excesivas a /api/auth/register.
     */
    public function test_api_auth_register_rate_limiting_blocks_after_five_attempts(): void
    {
        $payload = [
            'name' => 'Spam Bot',
            'email' => 'spam@ejemplo.com',
            'password' => 'secret123',
        ];

        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson(route('api.auth.register'), $payload);
            // 422 por confirmación de password faltante
            $this->assertEquals(422, $response->status());
        }

        // El 6to intento debe devolver 429
        $blocked = $this->postJson(route('api.auth.register'), $payload);

        $blocked->assertStatus(429)
            ->assertJson([
                'success' => false,
                'message' => 'Demasiados intentos de autenticación en la API. Por favor intente más tarde.',
            ]);
    }

    /**
     * Verifica que el inicio de sesión web esté protegido contra ataques de fuerza bruta.
     */
    public function test_web_login_rate_limiting_protects_against_brute_force(): void
    {
        $payload = [
            'email' => 'brute@ejemplo.com',
            'password' => 'clave-falsa',
        ];

        for ($i = 0; $i < 5; $i++) {
            $response = $this->from(route('login'))->post(route('login'), $payload);
            $response->assertStatus(302); // Redirección con error de credenciales
        }

        // El 6to intento debe recibir HTTP 429
        $blocked = $this->from(route('login'))->post(route('login'), $payload);
        $blocked->assertStatus(429);
    }

    /**
     * Verifica que la solicitud de recuperación de contraseña tenga rate limiting estricto (3 intentos).
     */
    public function test_password_reset_rate_limiting_blocks_after_three_attempts(): void
    {
        $payload = ['email' => 'victima@ejemplo.com'];

        for ($i = 0; $i < 3; $i++) {
            $this->from(route('password.request'))->post(route('password.email'), $payload);
        }

        // El 4to intento debe ser bloqueado con 429
        $blocked = $this->from(route('password.request'))->post(route('password.email'), $payload);
        $blocked->assertStatus(429);
    }

    /**
     * Verifica que el endpoint de disponibilidad incluya las cabeceras de límite de tasa.
     */
    public function test_availability_endpoint_includes_rate_limit_headers(): void
    {
        $response = $this->getJson(route('canchas.disponibilidad', [
            'cancha' => 1,
            'fecha' => '2026-11-20',
        ]));

        $response->assertOk()
            ->assertHeader('X-RateLimit-Limit', '60');
    }

    /**
     * Verifica que las rutas autenticadas con JWT tengan asignado el limitador general de API.
     */
    public function test_api_authenticated_routes_include_api_rate_limiting(): void
    {
        $user = new User([
            'name' => 'Cliente Test',
            'email' => 'cliente_test@ejemplo.com',
            'rol' => 'cliente',
            'activo' => true,
        ]);
        $user->id = 55;

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson(route('api.auth.me'));

        $response->assertOk()
            ->assertHeader('X-RateLimit-Limit', '60')
            ->assertJsonPath('data.id', 55);
    }
}
