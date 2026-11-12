<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Pago;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropietarioReporteController extends Controller
{
    public function index(Request $request)
    {
        $propietarioId = Auth::id();
        $establecimientoIds = Establecimiento::where('propietario_id', $propietarioId)->pluck('id');
        $canchaIds = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id');

        $desde = $request->query('desde', Carbon::now('America/Lima')->startOfMonth()->format('Y-m-d'));
        $hasta = $request->query('hasta', Carbon::now('America/Lima')->endOfMonth()->format('Y-m-d'));

        $inicioPeriodo = Carbon::parse($desde.' 00:00:00', 'America/Lima');
        $finPeriodo = Carbon::parse($hasta.' 23:59:59', 'America/Lima');

        // Total ingresos cobrados en el periodo
        $totalIngresos = Pago::whereHas('reserva', function ($q) use ($canchaIds) {
            $q->whereIn('cancha_id', $canchaIds);
        })
            ->where('estado', 'pagado')
            ->whereBetween('pagado_en', [$inicioPeriodo, $finPeriodo])
            ->sum('monto');

        // Total pendiente de cobro en el periodo
        $totalPendiente = Pago::whereHas('reserva', function ($q) use ($canchaIds, $inicioPeriodo, $finPeriodo) {
            $q->whereIn('cancha_id', $canchaIds)
                ->whereBetween('inicio', [$inicioPeriodo, $finPeriodo])
                ->where('estado', 'confirmada');
        })
            ->where('estado', 'pendiente')
            ->sum('monto');

        // Total reservas en el periodo
        $totalReservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->whereBetween('inicio', [$inicioPeriodo, $finPeriodo])
            ->count();

        // Reservas por estado
        $reservasPorEstado = Reserva::whereIn('cancha_id', $canchaIds)
            ->whereBetween('inicio', [$inicioPeriodo, $finPeriodo])
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Ingresos por método de pago
        $ingresosPorMetodo = Pago::whereHas('reserva', function ($q) use ($canchaIds) {
            $q->whereIn('cancha_id', $canchaIds);
        })
            ->where('estado', 'pagado')
            ->whereBetween('pagado_en', [$inicioPeriodo, $finPeriodo])
            ->select('metodo', DB::raw('sum(monto) as total_monto'), DB::raw('count(*) as transacciones'))
            ->groupBy('metodo')
            ->get();

        // Canchas con más reservas
        $canchasPopulares = Reserva::whereIn('cancha_id', $canchaIds)
            ->whereBetween('inicio', [$inicioPeriodo, $finPeriodo])
            ->select('cancha_id', DB::raw('count(*) as total_reservas'), DB::raw('sum(precio_total) as total_generado'))
            ->groupBy('cancha_id')
            ->with('cancha.establecimiento')
            ->orderBy('total_reservas', 'desc')
            ->get();

        return view('propietario.reportes.index', compact(
            'desde',
            'hasta',
            'totalIngresos',
            'totalPendiente',
            'totalReservas',
            'reservasPorEstado',
            'ingresosPorMetodo',
            'canchasPopulares'
        ));
    }
}
