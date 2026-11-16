<?php

namespace App\Services;

use App\Events\DisponibilidadCanchaActualizada;
use App\Events\ReservaPropietarioNotificada;
use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Servicio centralizado para emitir eventos WebSocket sin interrumpir transacciones HTTP si Reverb no está activo.
 */
class RealtimeNotificationService
{
    /**
     * Emite la actualización de disponibilidad horaria de una cancha para una fecha específica.
     */
    public function notificarDisponibilidadCancha(int $canchaId, string $fecha, string $accion, string $mensaje): void
    {
        try {
            DisponibilidadCanchaActualizada::dispatch($canchaId, $fecha, $accion, $mensaje);
        } catch (Throwable $exception) {
            Log::warning('No se pudo transmitir la disponibilidad por WebSocket.', [
                'cancha_id' => $canchaId,
                'fecha' => $fecha,
                'accion' => $accion,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Emite la notificación de reserva al canal privado del propietario y actualiza la disponibilidad de la cancha.
     */
    public function notificarCambioReserva(Reserva $reserva, string $accion, string $mensajePropietario, string $mensajeCancha): void
    {
        $reserva->loadMissing(['cancha.establecimiento', 'cliente']);

        $cancha = $reserva->cancha;
        $fecha = $reserva->inicio->copy()->setTimezone('America/Lima')->format('Y-m-d');

        if ($cancha instanceof Cancha) {
            $this->notificarDisponibilidadCancha($cancha->id, $fecha, $accion, $mensajeCancha);

            try {
                ReservaPropietarioNotificada::dispatch(
                    $cancha->establecimiento_id,
                    $reserva->codigo,
                    $cancha->nombre,
                    $reserva->cliente?->name ?? 'Cliente',
                    $reserva->inicio->copy()->setTimezone('America/Lima')->format('d/m/Y H:i'),
                    (float) $reserva->precio_total,
                    $accion,
                    $mensajePropietario,
                );
            } catch (Throwable $exception) {
                Log::warning('No se pudo transmitir la notificación de reserva al propietario.', [
                    'reserva_id' => $reserva->id,
                    'establecimiento_id' => $cancha->establecimiento_id,
                    'accion' => $accion,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }
}
