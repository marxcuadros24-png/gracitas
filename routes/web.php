<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReporteController;
use App\Http\Controllers\AdminSupervisionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanchaPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropietarioBloqueoController;
use App\Http\Controllers\PropietarioCanchaController;
use App\Http\Controllers\PropietarioDashboardController;
use App\Http\Controllers\PropietarioEstablecimientoController;
use App\Http\Controllers\PropietarioHorarioController;
use App\Http\Controllers\PropietarioPagoController;
use App\Http\Controllers\PropietarioReporteController;
use App\Http\Controllers\PropietarioReservaController;
use App\Http\Controllers\ReservaClienteController;
use App\Http\Controllers\SwaggerDocController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/documentation', [SwaggerDocController::class, 'index'])->name('swagger.ui');
Route::get('/api/openapi.json', [SwaggerDocController::class, 'openapi'])->name('swagger.spec');
Route::get('/establecimiento/{establecimiento}', [CanchaPublicController::class, 'showEstablecimiento'])->name('canchas.establecimiento');
Route::get('/cancha/{cancha}', [CanchaPublicController::class, 'showCancha'])->name('canchas.detalle');
Route::get('/cancha/{cancha}/disponibilidad', [CanchaPublicController::class, 'getDisponibilidadApi'])->name('canchas.disponibilidad');

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Autenticación, Recuperación de Contraseña y Verificación de Correo
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Recuperación de Contraseña
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Verificación de Correo Electrónico
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
    Route::post('/email/actualizar-correo', [EmailVerificationController::class, 'updateEmail'])->middleware('throttle:6,1')->name('verification.update-email');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/perfil', [AuthController::class, 'profile'])->name('profile');
    Route::put('/perfil', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/perfil', [AuthController::class, 'destroyProfile'])->name('profile.destroy');
    Route::get('/reserva/comprobante/{codigo}', [ReservaClienteController::class, 'comprobante'])->name('cliente.reserva.comprobante');
});

/*
|--------------------------------------------------------------------------
| Rutas del Cliente
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/reservas', [ReservaClienteController::class, 'misReservas'])->name('reservas');
    Route::get('/reservar/{cancha}', [ReservaClienteController::class, 'create'])->name('reservar.paso2');
    Route::post('/reservar', [ReservaClienteController::class, 'store'])->name('reservar.store');
    Route::post('/reserva/{reserva}/cancelar', [ReservaClienteController::class, 'cancelar'])->name('reserva.cancelar');
});

/*
|--------------------------------------------------------------------------
| Rutas del Propietario
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:propietario'])->prefix('propietario')->name('propietario.')->group(function () {
    Route::get('/dashboard', [PropietarioDashboardController::class, 'index'])->name('dashboard');

    // Gestión de establecimientos
    Route::resource('establecimientos', PropietarioEstablecimientoController::class)->except(['show', 'destroy']);

    // Gestión de canchas
    Route::resource('canchas', PropietarioCanchaController::class)->except(['show', 'destroy']);

    // Horarios de atención
    Route::get('/canchas/{cancha}/horarios', [PropietarioHorarioController::class, 'edit'])->name('horarios.edit');
    Route::put('/canchas/{cancha}/horarios', [PropietarioHorarioController::class, 'update'])->name('horarios.update');

    // Bloqueos de horarios
    Route::get('/bloqueos', [PropietarioBloqueoController::class, 'index'])->name('bloqueos.index');
    Route::post('/bloqueos', [PropietarioBloqueoController::class, 'store'])->name('bloqueos.store');
    Route::delete('/bloqueos/{bloqueo}', [PropietarioBloqueoController::class, 'destroy'])->name('bloqueos.destroy');

    // Reservas y reservas manuales
    Route::get('/reservas', [PropietarioReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/manual', [PropietarioReservaController::class, 'create'])->name('reservas.manual');
    Route::post('/reservas/manual', [PropietarioReservaController::class, 'storeManual'])->name('reservas.manual.store');
    Route::patch('/reservas/{reserva}/estado', [PropietarioReservaController::class, 'cambiarEstado'])->name('reservas.cambiarEstado');

    // Gestión de pagos presenciales
    Route::get('/pagos', [PropietarioPagoController::class, 'index'])->name('pagos.index');
    Route::patch('/pagos/{pago}/registrar', [PropietarioPagoController::class, 'registrarPago'])->name('pagos.registrar');

    // Reportes de ingresos y reservas
    Route::get('/reportes', [PropietarioReporteController::class, 'index'])->name('reportes.index');
});

/*
|--------------------------------------------------------------------------
| Rutas del Administrador
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::resource('usuarios', AdminUserController::class)->except(['show']);
    Route::patch('/usuarios/{usuario}/toggle', [AdminUserController::class, 'toggleActivo'])->name('usuarios.toggle');

    // Supervisión general
    Route::get('/supervision/establecimientos', [AdminSupervisionController::class, 'establecimientos'])->name('supervision.establecimientos');
    Route::get('/supervision/canchas', [AdminSupervisionController::class, 'canchas'])->name('supervision.canchas');
    Route::get('/supervision/reservas', [AdminSupervisionController::class, 'reservas'])->name('supervision.reservas');
    Route::get('/supervision/pagos', [AdminSupervisionController::class, 'pagos'])->name('supervision.pagos');

    // Reportes generales
    Route::get('/reportes', [AdminReporteController::class, 'index'])->name('reportes.index');
});
