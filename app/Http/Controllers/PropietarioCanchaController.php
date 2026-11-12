<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\HorarioAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropietarioCanchaController extends Controller
{
    public function index()
    {
        $establecimiento = Auth::user()->establecimiento;
        $canchas = $establecimiento
            ? $establecimiento->canchas()->with(['establecimiento', 'horariosAtencion'])->orderBy('id', 'desc')->get()
            : collect();

        return view('propietario.canchas.index', compact('canchas', 'establecimiento'));
    }

    public function create()
    {
        $establecimiento = Auth::user()->establecimiento;

        if (! $establecimiento || ! $establecimiento->activo) {
            return redirect()->route('propietario.establecimientos.create')
                ->with('warning', 'Primero debes registrar tu local deportivo activo para poder añadir canchas.');
        }

        return view('propietario.canchas.create', compact('establecimiento'));
    }

    public function store(Request $request)
    {
        $establecimiento = Auth::user()->establecimiento;

        if (! $establecimiento) {
            return redirect()->route('propietario.establecimientos.create')
                ->with('error', 'Primero debes registrar tu local deportivo para añadir canchas.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:2|max:30',
            'precio_hora' => 'required|numeric|min:0',
            'duracion_reserva_min' => 'required|integer|in:60,90,120',
        ], [
            'nombre.required' => 'El nombre de la cancha es obligatorio.',
            'precio_hora.required' => 'El precio por hora es obligatorio.',
        ]);

        $validated['establecimiento_id'] = $establecimiento->id;
        $validated['activa'] = 1;

        $cancha = Cancha::create($validated);

        // Crear horarios por defecto de Lunes a Domingo (07:00 a 23:00)
        for ($dia = 1; $dia <= 7; $dia++) {
            HorarioAtencion::create([
                'cancha_id' => $cancha->id,
                'dia_semana' => $dia,
                'hora_apertura' => '07:00:00',
                'hora_cierre' => '23:00:00',
                'activo' => 1,
            ]);
        }

        return redirect()->route('propietario.canchas.index')
            ->with('success', 'Cancha registrada exitosamente en tu local con horarios de atención predeterminados.');
    }

    public function edit(Cancha $cancha)
    {
        $establecimiento = Auth::user()->establecimiento;

        if (! $establecimiento || $cancha->establecimiento_id !== $establecimiento->id) {
            abort(403);
        }

        return view('propietario.canchas.edit', compact('cancha', 'establecimiento'));
    }

    public function update(Request $request, Cancha $cancha)
    {
        $establecimiento = Auth::user()->establecimiento;

        if (! $establecimiento || $cancha->establecimiento_id !== $establecimiento->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:2|max:30',
            'precio_hora' => 'required|numeric|min:0',
            'duracion_reserva_min' => 'required|integer|in:60,90,120',
            'activa' => 'required|boolean',
        ]);

        $cancha->update($validated);

        return redirect()->route('propietario.canchas.index')
            ->with('success', 'Cancha actualizada correctamente.');
    }
}
