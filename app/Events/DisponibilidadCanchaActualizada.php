<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento en tiempo real cuando cambia la disponibilidad horaria de una cancha.
 */
class DisponibilidadCanchaActualizada implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public int $canchaId,
        public string $fecha,
        public string $accion,
        public string $mensaje,
    ) {}

    /**
     * Canal público de la cancha afectada.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('cancha.'.$this->canchaId),
        ];
    }

    /**
     * Nombre personalizado del evento transmitido por WebSockets.
     */
    public function broadcastAs(): string
    {
        return 'disponibilidad.actualizada';
    }
}

