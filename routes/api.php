<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autenticación con JWT (JSON Web Tokens)
|--------------------------------------------------------------------------
|
| Endpoints para emisión, renovación, consulta de perfil y revocación
| de tokens de seguridad JWT para aplicaciones móviles y clientes API.
|
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:api.auth')
        ->name('api.auth.login');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:api.auth')
        ->name('api.auth.register');

    Route::middleware(['auth:api', 'throttle:api'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });
});

Route::middleware(['auth:api', 'throttle:api'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'user' => $request->user(),
    ]);
});
