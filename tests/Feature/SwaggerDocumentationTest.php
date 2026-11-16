<?php

namespace Tests\Feature;

use Tests\TestCase;

class SwaggerDocumentationTest extends TestCase
{
    public function test_swagger_ui_documentation_page_is_accessible(): void
    {
        $this->get(route('swagger.ui'))
            ->assertOk()
            ->assertSee('GraCitas — Documentación API (Swagger / OpenAPI 3.0)')
            ->assertSee('swagger-ui-bundle.js')
            ->assertSee(route('swagger.spec'), false);
    }

    public function test_openapi_specification_returns_valid_swagger_schema(): void
    {
        $this->getJson(route('swagger.spec'))
            ->assertOk()
            ->assertJsonPath('openapi', '3.0.3')
            ->assertJsonPath('info.version', '2.3.0')
            ->assertJsonStructure([
                'openapi',
                'info' => ['title', 'version', 'description'],
                'servers',
                'paths' => [
                    '/api/auth/login',
                    '/api/auth/register',
                    '/api/auth/me',
                    '/api/auth/refresh',
                    '/api/auth/logout',
                    '/cancha/{cancha}/disponibilidad',
                    '/api/user',
                    '/broadcasting/auth',
                ],
                'components' => [
                    'securitySchemes' => ['bearerAuth', 'cookieAuth'],
                    'schemas' => [
                        'JwtLoginRequest',
                        'JwtRegisterRequest',
                        'JwtTokenResponse',
                        'SlotDisponibilidad',
                        'DisponibilidadResponse',
                        'User',
                        'RateLimitErrorResponse',
                    ],
                ],
            ]);
    }
}
