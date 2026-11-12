<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CanchaPublicController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function showEstablecimiento(Establecimiento $establecimiento)
    {
        if (! $establecimiento->activo) {
            abort(404, 'Establecimiento no disponible.');
        }

        $establecimiento->load(['canchas' => function ($q) {
            $q->where('activa', 1)->with('horariosAtencion');
        }, 'propietario']);

        return view('canchas.establecimiento', compact('establecimiento'));
    }

    public function showCancha(Request $request, Cancha $cancha)
    {
        if (! $cancha->activa || ! $cancha->establecimiento->activo) {
            abort(404, 'Cancha no disponible.');
        }

        $fechaSeleccionada = $request->query('fecha', Carbon::today('America/Lima')->format('Y-m-d'));

        $cancha->load(['establecimiento', 'horariosAtencion' => function ($q) {
            $q->where('activo', 1)->orderBy('dia_semana');
        }]);

        $disponibilidad = $this->availabilityService->getSlotsForDate($cancha, $fechaSeleccionada);

        return view('canchas.detalle', compact('cancha', 'fechaSeleccionada', 'disponibilidad'));
    }

    public function getDisponibilidadApi(Request $request, Cancha $cancha)
    {
        $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
        ]);

        $disponibilidad = $this->availabilityService->getSlotsForDate($cancha, $request->fecha);

        return response()->json($disponibilidad);
    }
}
