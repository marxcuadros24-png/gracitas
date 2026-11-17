<?php

namespace App\Services;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\HorarioAtencion;
use App\Models\Reserva;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Obtiene los horarios y estado de disponibilidad de una cancha para una fecha específica.
     */
    public function getSlotsForDate(Cancha $cancha, string $fecha): array
    {
        $date = Carbon::parse($fecha)->setTimezone('America/Lima');
        $diaSemana = $date->dayOfWeekIso; // 1 = Lunes, 7 = Domingo

        $horario = HorarioAtencion::where('cancha_id', $cancha->id)
            ->where('dia_semana', $diaSemana)
            ->where('activo', 1)
            ->first();

        if (! $horario) {
            return [
                'abierto' => false,
                'mensaje' => 'La cancha no atiende en el día seleccionado.',
                'slots' => [],
            ];
        }

        $duracionMin = $cancha->duracion_reserva_min > 0 ? $cancha->duracion_reserva_min : 60;
        $apertura = Carbon::parse($fecha.' '.$horario->hora_apertura, 'America/Lima');
        $cierre = Carbon::parse($fecha.' '.$horario->hora_cierre, 'America/Lima');

        $ahora = Carbon::now('America/Lima');

        // Obtener reservas activas (confirmadas) para este día
        $reservas = Reserva::where('cancha_id', $cancha->id)
            ->where('estado', 'confirmada')
            ->where(function ($q) use ($apertura, $cierre) {
                $q->where('inicio', '<', $cierre)
                    ->where('fin', '>', $apertura);
            })
            ->with('cliente')
            ->get();

        // Obtener bloqueos de horario para este día
        $bloqueos = BloqueoHorario::where('cancha_id', $cancha->id)
            ->where(function ($q) use ($apertura, $cierre) {
                $q->where('inicio', '<', $cierre)
                    ->where('fin', '>', $apertura);
            })
            ->get();

        $slots = [];
        $actual = $apertura->copy();

        while ($actual->copy()->addMinutes($duracionMin)->lte($cierre)) {
            $slotInicio = $actual->copy();
            $slotFin = $actual->copy()->addMinutes($duracionMin);

            $esPasado = $slotInicio->lte($ahora);
            $reservaSolapada = $reservas->first(function ($r) use ($slotInicio, $slotFin) {
                return $r->inicio < $slotFin && $r->fin > $slotInicio;
            });
            $bloqueoSolapado = $bloqueos->first(function ($b) use ($slotInicio, $slotFin) {
                return $b->inicio < $slotFin && $b->fin > $slotInicio;
            });

            $disponible = true;
            $estado = 'disponible';
            $detalle = 'Disponible para reserva';

            if ($esPasado) {
                $disponible = false;
                $estado = 'pasado';
                $detalle = 'Horario pasado';
            } elseif ($reservaSolapada) {
                $disponible = false;
                $estado = 'reservado';
                $detalle = 'Horario no disponible';
            } elseif ($bloqueoSolapado) {
                $disponible = false;
                $estado = 'bloqueado';
                $detalle = 'Mantenimiento / Bloqueado: '.($bloqueoSolapado->motivo ?: 'Sin motivo');
            }

            $slots[] = [
                'inicio_hora' => $slotInicio->format('H:i'),
                'fin_hora' => $slotFin->format('H:i'),
                'inicio_iso' => $slotInicio->format('Y-m-d H:i:s'),
                'fin_iso' => $slotFin->format('Y-m-d H:i:s'),
                'disponible' => $disponible,
                'estado' => $estado,
                'detalle' => $detalle,
                'precio' => (float) $cancha->precio_hora,
            ];

            $actual->addMinutes($duracionMin);
        }

        return [
            'abierto' => true,
            'hora_apertura' => $horario->hora_apertura,
            'hora_cierre' => $horario->hora_cierre,
            'slots' => $slots,
        ];
    }

    /**
     * Valida si un intervalo específico de tiempo está completamente disponible para una cancha.
     */
    public function checkAvailability(Cancha $cancha, Carbon $inicio, Carbon $fin, ?int $excludeReservaId = null): array
    {
        if ($inicio->gte($fin)) {
            return [
                'disponible' => false,
                'mensaje' => 'La hora de inicio debe ser anterior a la hora de fin.',
            ];
        }

        $ahora = Carbon::now('America/Lima');
        if ($inicio->lte($ahora)) {
            return [
                'disponible' => false,
                'mensaje' => 'No es posible reservar en fechas u horarios pasados.',
            ];
        }

        // Validar horario de atención del día
        $diaSemana = $inicio->dayOfWeekIso;
        $horario = HorarioAtencion::where('cancha_id', $cancha->id)
            ->where('dia_semana', $diaSemana)
            ->where('activo', 1)
            ->first();

        if (! $horario) {
            return [
                'disponible' => false,
                'mensaje' => 'La cancha se encuentra cerrada el día seleccionado.',
            ];
        }

        $apertura = Carbon::parse($inicio->format('Y-m-d').' '.$horario->hora_apertura, 'America/Lima');
        $cierre = Carbon::parse($inicio->format('Y-m-d').' '.$horario->hora_cierre, 'America/Lima');

        if ($inicio->lt($apertura) || $fin->gt($cierre)) {
            return [
                'disponible' => false,
                'mensaje' => "El horario seleccionado está fuera del rango de atención ({$horario->hora_apertura} a {$horario->hora_cierre}).",
            ];
        }

        // Validar reservas solapadas
        $queryReserva = Reserva::where('cancha_id', $cancha->id)
            ->where('estado', 'confirmada')
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('inicio', '<', $fin)
                    ->where('fin', '>', $inicio);
            });

        if ($excludeReservaId) {
            $queryReserva->where('id', '!=', $excludeReservaId);
        }

        $conflictoReserva = $queryReserva->exists();
        if ($conflictoReserva) {
            return [
                'disponible' => false,
                'mensaje' => 'El horario seleccionado ya se encuentra reservado por otro cliente.',
            ];
        }

        // Validar bloqueos solapados
        $conflictoBloqueo = BloqueoHorario::where('cancha_id', $cancha->id)
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('inicio', '<', $fin)
                    ->where('fin', '>', $inicio);
            })->first();

        if ($conflictoBloqueo) {
            return [
                'disponible' => false,
                'mensaje' => 'El horario seleccionado está bloqueado por el establecimiento ('.($conflictoBloqueo->motivo ?: 'Mantenimiento').').',
            ];
        }

        return [
            'disponible' => true,
            'mensaje' => 'Horario disponible.',
        ];
    }
}
