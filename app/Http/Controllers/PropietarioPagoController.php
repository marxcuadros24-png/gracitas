<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropietarioPagoController extends Controller
{
    public function index(Request $request)
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $canchaIds = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id');

        $query = Pago::whereHas('reserva', function ($q) use ($canchaIds) {
            $q->whereIn('cancha_id', $canchaIds);
        })
            ->with(['reserva.cliente', 'reserva.cancha.establecimiento', 'registradoPor'])
            ->orderBy('id', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('metodo')) {
            $query->where('metodo', $request->metodo);
        }

        $pagos = $query->paginate(15);

        return view('propietario.pagos.index', compact('pagos'));
    }

    public function registrarPago(Request $request, Pago $pago)
    {
        if ($pago->reserva->cancha->establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'metodo' => 'required|in:efectivo,yape,plin,otro',
            'monto' => 'required|numeric|min:0',
            'observacion' => 'nullable|string|max:255',
        ]);

        $pago->update([
            'monto' => $validated['monto'],
            'metodo' => $validated['metodo'],
            'estado' => 'pagado',
            'pagado_en' => Carbon::now('America/Lima'),
            'registrado_por' => Auth::id(),
            'observacion' => $validated['observacion'] ?? 'Pago presencial recibido en ventanilla.',
        ]);

        // Si la reserva estaba confirmada, se mantiene o puede considerarse lista
        return back()->with('success', '¡Pago registrado exitosamente! La reserva figura ahora como pagada.');
    }
}
