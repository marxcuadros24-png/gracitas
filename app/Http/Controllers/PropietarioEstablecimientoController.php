<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropietarioEstablecimientoController extends Controller
{
    public function index()
    {
        $establecimiento = Establecimiento::where('propietario_id', Auth::id())
            ->withCount('canchas')
            ->first();

        $establecimientos = $establecimiento ? collect([$establecimiento]) : collect();

        return view('propietario.establecimientos.index', compact('establecimiento', 'establecimientos'));
    }

    public function create()
    {
        $existe = Establecimiento::where('propietario_id', Auth::id())->first();

        if ($existe) {
            return redirect()->route('propietario.establecimientos.edit', $existe->id)
                ->with('warning', 'Ya tienes un local registrado. Cada propietario administra un único local deportivo.');
        }

        return view('propietario.establecimientos.create');
    }

    public function store(Request $request)
    {
        if (Establecimiento::where('propietario_id', Auth::id())->exists()) {
            return redirect()->route('propietario.establecimientos.index')
                ->with('error', 'Solo puedes registrar un único local o establecimiento.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
        ], [
            'nombre.required' => 'El nombre del establecimiento es obligatorio.',
            'direccion.required' => 'La dirección en Coracora es obligatoria.',
        ]);

        $validated['propietario_id'] = Auth::id();
        $validated['activo'] = $request->has('activo') ? 1 : 1;

        Establecimiento::create($validated);

        return redirect()->route('propietario.establecimientos.index')
            ->with('success', 'Local deportivo registrado correctamente.');
    }

    public function edit(Establecimiento $establecimiento)
    {
        if ($establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        return view('propietario.establecimientos.edit', compact('establecimiento'));
    }

    public function update(Request $request, Establecimiento $establecimiento)
    {
        if ($establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'activo' => 'required|boolean',
        ]);

        $establecimiento->update($validated);

        return redirect()->route('propietario.establecimientos.index')
            ->with('success', 'Establecimiento actualizado con éxito.');
    }
}
