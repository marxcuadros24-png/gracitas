<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->query('desde', Carbon::now('America/Lima')->startOfMonth()->format('Y-m-d'));
        $hasta = $request->query('hasta', Carbon::now('America/Lima')->endOfMonth()->format('Y-m-d'));

        $inicioPeriodo = Carbon::parse($desde.' 00:00:00', 'America/Lima');
        $finPeriodo = Carbon::parse($hasta.' 23:59:59', 'America/Lima');

        // Total ingresos recaudados globalmente en el periodo
        $totalIngresosGlobal = Pago::where('estado', 'pagado')
            ->whereBetween('pagado_en', [$inicioPeriodo, $finPeriodo])
            ->sum('monto');

        // Total reservas en el periodo
        $totalReservasGlobal = Reserva::whereBetween('inicio', [$inicioPeriodo, $finPeriodo])->count();

        // Reservas por estado
        $reservasPorEstado = Reserva::whereBetween('inicio', [$inicioPeriodo, $finPeriodo])
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        // Ingresos por método de pago
        $ingresosPorMetodo = Pago::where('estado', 'pagado')
            ->whereBetween('pagado_en', [$inicioPeriodo, $finPeriodo])
            ->select('metodo', DB::raw('sum(monto) as total_monto'), DB::raw('count(*) as transacciones'))
            ->groupBy('metodo')
            ->get();

        // Ranking de establecimientos por reservas e ingresos
        $rankingEstablecimientos = Establecimiento::withCount(['reservas' => function ($q) use ($inicioPeriodo, $finPeriodo) {
            $q->whereBetween('inicio', [$inicioPeriodo, $finPeriodo]);
        }])
            ->with('propietario')
            ->orderBy('reservas_count', 'desc')
            ->get();

        // Estadísticas de usuarios
        $usuariosPorRol = User::select('rol', DB::raw('count(*) as total'))
            ->groupBy('rol')
            ->get();

        return view('admin.reportes.index', compact(
            'desde',
            'hasta',
            'totalIngresosGlobal',
            'totalReservasGlobal',
            'reservasPorEstado',
            'ingresosPorMetodo',
            'rankingEstablecimientos',
            'usuariosPorRol'
        ));
    }
}
