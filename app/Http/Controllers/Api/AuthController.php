<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Iniciar sesión en la API y obtener token JWT.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas. Por favor verifique su correo y contraseña.',
            ], 401);
        }

        /** @var User $user */
        $user = auth('api')->user();

        if (! $user->activo) {
            auth('api')->logout();

            return response()->json([
                'success' => false,
                'message' => 'Su cuenta se encuentra inactiva. Comuníquese con el administrador.',
            ], 403);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Registro de nuevo usuario a través de la API y retorno de token JWT.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rol' => ['nullable', 'string', 'in:cliente,propietario'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'rol' => $validated['rol'] ?? 'cliente',
            'activo' => true,
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token, $user, 201);
    }

    /**
     * Obtener el perfil del usuario autenticado con JWT.
     */
    public function me(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'telefono' => $user->telefono,
                'activo' => (bool) $user->activo,
                'email_verified' => $user->hasVerifiedEmail(),
                'created_at' => $user->created_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Renovar el token JWT actual.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = auth('api')->refresh();
            /** @var User $user */
            $user = auth('api')->user();

            return $this->respondWithToken($newToken, $user);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo renovar el token. Inicie sesión nuevamente.',
            ], 401);
        }
    }

    /**
     * Invalidar el token JWT actual y cerrar sesión.
     */
    public function logout(): JsonResponse
    {
        try {
            auth('api')->logout();
        } catch (\Throwable $e) {
            // Si no se puede parsear o ya expiró, se asegura el cierre de sesión
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente en la API.',
        ]);
    }

    /**
     * Estructura estándar de respuesta con token JWT.
     */
    protected function respondWithToken(string $token, User $user, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'telefono' => $user->telefono,
                'activo' => (bool) $user->activo,
                'email_verified' => $user->hasVerifiedEmail(),
            ],
        ], $status);
    }
}
