<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

/**
 * Controlador encargado de servir la interfaz Swagger UI y la especificación OpenAPI 3.0 de GraCitas.
 */
class SwaggerDocController extends Controller
{
    /**
     * Muestra la interfaz interactiva de Swagger UI.
     */
    public function index(): View
    {
        return view('docs.swagger', [
            'specUrl' => route('swagger.spec'),
        ]);
    }

    /**
     * Devuelve el documento OpenAPI 3.0.3 en formato JSON.
     */
    public function openapi(): JsonResponse
    {
        $spec = [
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'GraCitas API — Gestión y Reserva de Canchas de Gras Sintético',
                'version' => '2.2.0',
                'description' => "Documentación oficial de los servicios JSON REST, autenticación JWT y eventos en tiempo real (WebSockets vía Laravel Reverb) de la plataforma GraCitas.\n\n"
                    ."### Seguridad y Autenticación JWT\n"
                    ."- Todos los endpoints bajo `/api/auth/*` y `/api/user` utilizan **JSON Web Tokens (JWT)** con algoritmo HS256 y firma criptográfica.\n"
                    ."- Envíe el token en la cabecera HTTP: `Authorization: Bearer {access_token}`.\n\n"
                    ."### Canales WebSocket (Laravel Reverb — Puerto 8080)\n"
                    ."- **Canal Público `cancha.{canchaId}`** → Evento `.disponibilidad.actualizada`: notifica altas, cancelaciones o bloqueos horarios para refrescar los turnos en vivo.\n"
                    .'- **Canal Privado `private-establecimiento.{establecimientoId}`** → Evento `.reserva.notificada`: alerta en vivo al propietario autenticado sobre nuevas reservas o cancelaciones.',
            ],
            'servers' => [
                [
                    'url' => url('/'),
                    'description' => 'Servidor activo de GraCitas',
                ],
            ],
            'tags' => [
                [
                    'name' => 'Autenticación JWT',
                    'description' => 'Emisión, verificación, refresco y revocación de tokens JWT (JSON Web Tokens).',
                ],
                [
                    'name' => 'Disponibilidad Pública',
                    'description' => 'Consulta en vivo de franjas horarias de canchas deportivas.',
                ],
                [
                    'name' => 'WebSockets (Laravel Reverb)',
                    'description' => 'Autorización de canales privados para difusión en tiempo real.',
                ],
            ],
            'paths' => [
                '/cancha/{cancha}/disponibilidad' => [
                    'get' => [
                        'tags' => ['Disponibilidad Pública'],
                        'summary' => 'Consultar disponibilidad horaria de una cancha',
                        'description' => 'Devuelve si la cancha atiende en la fecha indicada y el listado completo de franjas horarias con su estado (`disponible`, `reservado`, `bloqueado` o `pasado`).',
                        'parameters' => [
                            [
                                'name' => 'cancha',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'ID numérico de la cancha deportiva',
                                'schema' => [
                                    'type' => 'integer',
                                    'example' => 1,
                                ],
                            ],
                            [
                                'name' => 'fecha',
                                'in' => 'query',
                                'required' => true,
                                'description' => 'Fecha a consultar en formato YYYY-MM-DD',
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'date',
                                    'example' => date('Y-m-d'),
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Disponibilidad obtenida correctamente',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/DisponibilidadResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '404' => [
                                'description' => 'Cancha no encontrada',
                            ],
                            '422' => [
                                'description' => 'Fecha inválida o faltante (formato requerido: Y-m-d)',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/ValidationErrorResponse',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/auth/login' => [
                    'post' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Iniciar sesión y obtener token JWT',
                        'description' => 'Verifica credenciales del usuario y emite un token JWT (JSON Web Token) válido para autenticar solicitudes API.',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/JwtLoginRequest',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Autenticación exitosa y emisión de token JWT',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/JwtTokenResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Credenciales inválidas',
                            ],
                            '403' => [
                                'description' => 'Cuenta inactiva',
                            ],
                        ],
                    ],
                ],
                '/api/auth/register' => [
                    'post' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Registrar nuevo usuario vía API y obtener token JWT',
                        'description' => 'Crea una nueva cuenta de cliente o propietario y devuelve un token JWT listo para usar.',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/JwtRegisterRequest',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Usuario registrado exitosamente',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/JwtTokenResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '422' => [
                                'description' => 'Errores de validación de formulario',
                            ],
                        ],
                    ],
                ],
                '/api/auth/me' => [
                    'get' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Consultar perfil del usuario autenticado con JWT',
                        'description' => 'Retorna los datos del usuario correspondiente al token Bearer JWT enviado.',
                        'security' => [
                            ['bearerAuth' => []],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Perfil del usuario autenticado',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/User',
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Token JWT ausente o inválido',
                            ],
                        ],
                    ],
                ],
                '/api/auth/refresh' => [
                    'post' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Renovar token JWT',
                        'description' => 'Genera un nuevo token JWT a partir del token actual enviado en la cabecera Authorization.',
                        'security' => [
                            ['bearerAuth' => []],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Token JWT renovado exitosamente',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/JwtTokenResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Token no renovable o expirado',
                            ],
                        ],
                    ],
                ],
                '/api/auth/logout' => [
                    'post' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Cerrar sesión y revocar token JWT',
                        'description' => 'Invalida el token JWT actual incluyéndolo en la lista negra (blacklist) para impedir su reutilización.',
                        'security' => [
                            ['bearerAuth' => []],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Sesión revocada exitosamente',
                            ],
                            '401' => [
                                'description' => 'Token inválido',
                            ],
                        ],
                    ],
                ],
                '/api/user' => [
                    'get' => [
                        'tags' => ['Autenticación JWT'],
                        'summary' => 'Obtener perfil del usuario autenticado vía JWT',
                        'description' => 'Retorna la información básica del usuario vinculado al token Bearer JWT (`auth:api`).',
                        'security' => [
                            ['bearerAuth' => []],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Datos del usuario autenticado',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/User',
                                        ],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'No autenticado (token inválido o ausente)',
                            ],
                        ],
                    ],
                ],
                '/broadcasting/auth' => [
                    'post' => [
                        'tags' => ['WebSockets (Laravel Reverb)'],
                        'summary' => 'Autorizar suscripción a canal privado WebSocket',
                        'description' => 'Valida la sesión del usuario conectado para autorizar su suscripción a canales privados como `private-establecimiento.{establecimientoId}`.',
                        'security' => [
                            ['cookieAuth' => []],
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/BroadcastingAuthRequest',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Firma criptográfica de autorización emitida para Reverb',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            '$ref' => '#/components/schemas/BroadcastingAuthResponse',
                                        ],
                                    ],
                                ],
                            ],
                            '403' => [
                                'description' => 'Usuario no autorizado para el establecimiento solicitado',
                            ],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'Token de seguridad JWT transmitido en la cabecera Authorization: Bearer {token}.',
                    ],
                    'cookieAuth' => [
                        'type' => 'apiKey',
                        'in' => 'cookie',
                        'name' => 'laravel_session',
                        'description' => 'Sesión web activa del usuario autenticado en GraCitas.',
                    ],
                ],
                'schemas' => [
                    'JwtLoginRequest' => [
                        'type' => 'object',
                        'required' => ['email', 'password'],
                        'properties' => [
                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'cliente@gracitas.com'],
                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'cliente123'],
                        ],
                    ],
                    'JwtRegisterRequest' => [
                        'type' => 'object',
                        'required' => ['name', 'email', 'password', 'password_confirmation'],
                        'properties' => [
                            'name' => ['type' => 'string', 'example' => 'Carlos Mendoza Huamaní'],
                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'carlos.mendoza@gmail.com'],
                            'password' => ['type' => 'string', 'format' => 'password', 'minLength' => 6, 'example' => 'segura123'],
                            'password_confirmation' => ['type' => 'string', 'format' => 'password', 'example' => 'segura123'],
                            'telefono' => ['type' => 'string', 'example' => '987654321'],
                            'rol' => ['type' => 'string', 'enum' => ['cliente', 'propietario'], 'default' => 'cliente', 'example' => 'cliente'],
                        ],
                    ],
                    'JwtTokenResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'success' => ['type' => 'boolean', 'example' => true],
                            'access_token' => ['type' => 'string', 'example' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'],
                            'token_type' => ['type' => 'string', 'example' => 'bearer'],
                            'expires_in' => ['type' => 'integer', 'example' => 3600],
                            'user' => [
                                '$ref' => '#/components/schemas/User',
                            ],
                        ],
                    ],
                    'SlotDisponibilidad' => [
                        'type' => 'object',
                        'properties' => [
                            'inicio_hora' => ['type' => 'string', 'example' => '18:00'],
                            'fin_hora' => ['type' => 'string', 'example' => '19:00'],
                            'inicio_iso' => ['type' => 'string', 'example' => '2026-11-12 18:00:00'],
                            'fin_iso' => ['type' => 'string', 'example' => '2026-11-12 19:00:00'],
                            'disponible' => ['type' => 'boolean', 'example' => true],
                            'estado' => ['type' => 'string', 'enum' => ['disponible', 'reservado', 'bloqueado', 'pasado'], 'example' => 'disponible'],
                            'detalle' => ['type' => 'string', 'example' => 'Disponible para reserva'],
                            'precio' => ['type' => 'number', 'format' => 'float', 'example' => 80.0],
                        ],
                    ],
                    'DisponibilidadResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'abierto' => ['type' => 'boolean', 'example' => true],
                            'mensaje' => ['type' => 'string', 'nullable' => true, 'example' => 'La cancha no atiende en el día seleccionado.'],
                            'hora_apertura' => ['type' => 'string', 'nullable' => true, 'example' => '07:00:00'],
                            'hora_cierre' => ['type' => 'string', 'nullable' => true, 'example' => '23:00:00'],
                            'slots' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/SlotDisponibilidad',
                                ],
                            ],
                        ],
                    ],
                    'User' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 3],
                            'name' => ['type' => 'string', 'example' => 'Carlos Mendoza'],
                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'cliente@gracitas.com'],
                            'telefono' => ['type' => 'string', 'nullable' => true, 'example' => '987654321'],
                            'rol' => ['type' => 'string', 'enum' => ['admin', 'propietario', 'cliente'], 'example' => 'cliente'],
                            'activo' => ['type' => 'boolean', 'example' => true],
                        ],
                    ],
                    'BroadcastingAuthRequest' => [
                        'type' => 'object',
                        'required' => ['socket_id', 'channel_name'],
                        'properties' => [
                            'socket_id' => ['type' => 'string', 'example' => '120334977.797307556'],
                            'channel_name' => ['type' => 'string', 'example' => 'private-establecimiento.1'],
                        ],
                    ],
                    'BroadcastingAuthResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'auth' => ['type' => 'string', 'example' => '2bwhnbtmmhouuhqgamln:a8f29c...'],
                        ],
                    ],
                    'ValidationErrorResponse' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => ['type' => 'string', 'example' => 'The fecha field is required.'],
                            'errors' => [
                                'type' => 'object',
                                'additionalProperties' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($spec);
    }
}
