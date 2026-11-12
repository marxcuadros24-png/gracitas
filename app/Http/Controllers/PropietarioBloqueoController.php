<?php

namespace App\Http\Controllers;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Services\RealtimeNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropietarioBloqueoController extends Controller
{
    public function __construct(
        protected RealtimeNotificationService $realtimeNotificationService,
    ) {}

    public function index()
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $canchaIds = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id');

        $bloqueos = BloqueoHorario::whereIn('cancha_id', $canchaIds)
            ->with(['cancha.establecimiento'])
            ->orderBy('inicio', 'desc')
            ->paginate(15);

        $canchas = Cancha::whereIn('id', $canchaIds)->where('activa', 1)->get();

        return view('propietario.bloqueos.index', compact('bloqueos', 'canchas'));
    }

    public function store(Request $request)
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $misCanchas = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id')->toArray();

        $validated = $request->validate([
            'cancha_id' => 'required|in:'.implode(',', $misCanchas),
            'fecha' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
            'motivo' => 'nullable|string|max:255',
        ], [
            'cancha_id.required' => 'Selecciona una cancha válida.',
            'fecha.required' => 'La fecha del bloqueo es requerida.',
        ]);

        $inicio = Carbon::parse($validated['fecha'].' '.$validated['hora_inicio'], 'America/Lima');
        $fin = Carbon::parse($validated['fecha'].' '.$validated['hora_fin'], 'America/Lima');

        if ($inicio->gte($fin)) {
            return back()->with('error', 'La hora de inicio debe ser anterior a la hora de fin.')->withInput();
        }

        BloqueoHorario::create([
            'cancha_id' => $validated['cancha_id'],
            'inicio' => $inicio,
            'fin' => $fin,
            'motivo' => $validated['motivo'] ?? 'Mantenimiento o evento privado',
            'creado_por' => Auth::id(),
        ]);

        $this->realtimeNotificationService->notificarDisponibilidadCancha(
            (int) $validated['cancha_id'],
            $validated['fecha'],
            'bloqueo_creado',
            'Se bloqueó una franja horaria por mantenimiento o evento privado.',
        );

        return back()->with('success', 'Franja horaria bloqueada satisfactoriamente.');
    }

    public function destroy(BloqueoHorario $bloqueo)
    {
        if ($bloqueo->cancha->establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        $canchaId = (int) $bloqueo->cancha_id;
        $fecha = Carbon::parse($bloqueo->inicio)->setTimezone('America/Lima')->format('Y-m-d');

        $bloqueo->delete();

        $this->realtimeNotificationService->notificarDisponibilidadCancha(
            $canchaId,
            $fecha,
            'bloqueo_eliminado',
            'Se liberó una franja horaria previamente bloqueada.',
        );

        return back()->with('success', 'El bloqueo ha sido retirado correctamente.');
    }
}
