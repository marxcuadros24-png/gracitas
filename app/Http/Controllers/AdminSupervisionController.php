<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSupervisionController extends Controller
{
    public function establecimientos(Request $request)
    {
        $query = Establecimiento::with(['propietario', 'canchas'])->orderBy('id', 'desc');

        if ($request->filled('propietario_id')) {
            $query->where('propietario_id', $request->propietario_id);
        }

        $establecimientos = $query->paginate(15);
        $propietarios = User::where('rol', 'propietario')->orderBy('name')->get();

        return view('admin.supervision.establecimientos', compact('establecimientos', 'propietarios'));
    }

    public function canchas(Request $request)
    {
        $query = Cancha::with(['establecimiento.propietario'])->orderBy('id', 'desc');

        if ($request->filled('establecimiento_id')) {
            $query->where('establecimiento_id', $request->establecimiento_id);
        }

        $canchas = $query->paginate(15);
        $establecimientos = Establecimiento::orderBy('nombre')->get();

        return view('admin.supervision.canchas', compact('canchas', 'establecimientos'));
    }

    public function reservas(Request $request)
    {
        $query = Reserva::with(['cliente', 'cancha.establecimiento.propietario', 'pago'])->orderBy('inicio', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('inicio', $request->fecha);
        }

        $reservas = $query->paginate(20);

        return view('admin.supervision.reservas', compact('reservas'));
    }

    public function pagos(Request $request)
    {
        $query = Pago::with(['reserva.cancha.establecimiento.propietario', 'reserva.cliente', 'registradoPor'])->orderBy('id', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $pagos = $query->paginate(20);

        return view('admin.supervision.pagos', compact('pagos'));
    }
}
