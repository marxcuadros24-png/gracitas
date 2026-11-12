<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\HorarioAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropietarioHorarioController extends Controller
{
    public function edit(Cancha $cancha)
    {
        if ($cancha->establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        // Asegurar que existan los 7 días
        for ($dia = 1; $dia <= 7; $dia++) {
            HorarioAtencion::firstOrCreate(
                ['cancha_id' => $cancha->id, 'dia_semana' => $dia],
                ['hora_apertura' => '07:00:00', 'hora_cierre' => '23:00:00', 'activo' => 1]
            );
        }

        $horarios = HorarioAtencion::where('cancha_id', $cancha->id)
            ->orderBy('dia_semana')
            ->get();

        return view('propietario.horarios.edit', compact('cancha', 'horarios'));
    }

    public function update(Request $request, Cancha $cancha)
    {
        if ($cancha->establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        $diasData = $request->input('horarios', []);

        foreach ($diasData as $diaSemana => $datos) {
            $horario = HorarioAtencion::where('cancha_id', $cancha->id)
                ->where('dia_semana', $diaSemana)
                ->first();

            if ($horario) {
                $apertura = $datos['hora_apertura'] ?? '07:00';
                $cierre = $datos['hora_cierre'] ?? '23:00';
                $activo = isset($datos['activo']) ? 1 : 0;

                // Validación simple de horas
                if ($apertura >= $cierre && $activo) {
                    return back()->with('error', "En el día {$horario->nombre_dia}, la hora de apertura debe ser menor a la hora de cierre.");
                }

                $horario->update([
                    'hora_apertura' => $apertura.':00',
                    'hora_cierre' => $cierre.':00',
                    'activo' => $activo,
                ]);
            }
        }

        return back()->with('success', 'Horarios de atención actualizados correctamente.');
    }
}
