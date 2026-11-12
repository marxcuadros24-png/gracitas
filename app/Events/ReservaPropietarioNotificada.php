<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento privado en tiempo real para notificar al propietario de un establecimiento sobre cambios en reservas.
 */
class ReservaPropietarioNotificada implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public int $establecimientoId,
        public string $codigo,
        public string $canchaNombre,
        public string $clienteNombre,
        public string $inicio,
        public float $precioTotal,
        public string $accion,
        public string $mensaje,
    ) {}

    /**
     * Canal privado del establecimiento propietario.
     *
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('establecimiento.'.$this->establecimientoId),
        ];
    }

    /**
     * Nombre personalizado del evento transmitido al propietario.
     */
    public function broadcastAs(): string
    {
        return 'reserva.notificada';
    }
}

