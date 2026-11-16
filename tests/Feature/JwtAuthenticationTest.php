<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticationTest extends TestCase
{
    /**
     * Verifica que el modelo User implemente el contrato JWTSubject.
     */
    public function test_user_model_implements_jwt_subject(): void
    {
        $user = new User([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos@ejemplo.com',
            'rol' => 'cliente',
            'activo' => true,
        ]);
        $user->id = 99;

        $this->assertInstanceOf(JWTSubject::class, $user);
        $this->assertSame(99, $user->getJWTIdentifier());

        $claims = $user->getJWTCustomClaims();
        $this->assertArrayHasKey('name', $claims);
        $this->assertArrayHasKey('email', $claims);
        $this->assertArrayHasKey('rol', $claims);
        $this->assertArrayHasKey('activo', $claims);
        $this->assertSame('cliente', $claims['rol']);
    }

    /**
     * Verifica que el guard 'api' esté configurado con el driver 'jwt'.
     */
    public function test_api_guard_uses_jwt_driver(): void
    {
        $guardConfig = config('auth.guards.api');

        $this->assertIsArray($guardConfig);
        $this->assertSame('jwt', $guardConfig['driver']);
        $this->assertSame('users', $guardConfig['provider']);
    }

    /**
     * Verifica la generación y decodificación de tokens JWT desde el usuario.
     */
    public function test_can_generate_and_decode_jwt_token_for_user(): void
    {
        $user = new User([
            'name' => 'Ana Gómez',
            'email' => 'ana@ejemplo.com',
            'rol' => 'propietario',
            'activo' => true,
        ]);
        $user->id = 101;

        $token = auth('api')->login($user);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        $payload = JWTAuth::setToken($token)->getPayload();

        $this->assertEquals(101, $payload->get('sub'));
        $this->assertSame('propietario', $payload->get('rol'));
        $this->assertSame('ana@ejemplo.com', $payload->get('email'));
    }

    /**
     * Verifica que la ruta /api/auth/me requiera autenticación JWT.
     */
    public function test_auth_me_endpoint_requires_jwt_token(): void
    {
        $response = $this->getJson(route('api.auth.me'));

        $response->assertStatus(401);
    }

    /**
     * Verifica que el endpoint /api/auth/me retorne los datos del usuario con token válido.
     */
    public function test_auth_me_returns_profile_when_authenticated(): void
    {
        $user = new User([
            'name' => 'Roberto Sánchez',
            'email' => 'roberto@ejemplo.com',
            'rol' => 'admin',
            'activo' => true,
        ]);
        $user->id = 7;

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson(route('api.auth.me'));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', 7)
            ->assertJsonPath('data.name', 'Roberto Sánchez')
            ->assertJsonPath('data.email', 'roberto@ejemplo.com')
            ->assertJsonPath('data.rol', 'admin');
    }

    /**
     * Verifica que el endpoint /api/auth/logout invalide la sesión y retorne mensaje exitoso.
     */
    public function test_logout_endpoint_closes_jwt_session(): void
    {
        $user = new User([
            'name' => 'Lucía Huamán',
            'email' => 'lucia@ejemplo.com',
            'rol' => 'cliente',
            'activo' => true,
        ]);
        $user->id = 15;

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('api.auth.logout'));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sesión cerrada exitosamente en la API.');
    }

    /**
     * Verifica la validación de campos requeridos en el login JWT.
     */
    public function test_login_validation_requires_email_and_password(): void
    {
        $response = $this->postJson(route('api.auth.login'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Verifica la validación de campos requeridos en el registro vía API.
     */
    public function test_register_validation_requires_mandatory_fields(): void
    {
        $response = $this->postJson(route('api.auth.register'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
