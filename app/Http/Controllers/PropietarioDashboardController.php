<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PropietarioDashboardController extends Controller
{
    public function index()
    {
        $propietarioId = Auth::id();
        $hoy = Carbon::today('America/Lima');

        // Local deportivo del propietario (1 local por propietario)
        $establecimiento = Auth::user()->establecimiento;
        $establecimientos = $establecimiento ? collect([$establecimiento]) : collect();
        $canchaIds = $establecimiento ? $establecimiento->canchas()->pluck('id') : collect();

        // Reservas de hoy
        $reservasHoy = Reserva::whereIn('cancha_id', $canchaIds)
            ->whereDate('inicio', $hoy)
            ->with(['cliente', 'cancha.establecimiento', 'pago'])
            ->orderBy('inicio', 'asc')
            ->get();

        // Próximas reservas (siguientes días)
        $proximasReservas = Reserva::whereIn('cancha_id', $canchaIds)
            ->where('inicio', '>=', $hoy->copy()->addDay()->startOfDay())
            ->where('estado', 'confirmada')
            ->with(['cliente', 'cancha.establecimiento', 'pago'])
            ->orderBy('inicio', 'asc')
            ->limit(5)
            ->get();

        // Métricas
        $totalReservasMes = Reserva::whereIn('cancha_id', $canchaIds)
            ->whereMonth('inicio', $hoy->month)
            ->whereYear('inicio', $hoy->year)
            ->where('estado', '!=', 'cancelada')
            ->count();

        // Ingresos del mes (pagados)
        $ingresosMes = Pago::whereHas('reserva', function ($q) use ($canchaIds) {
            $q->whereIn('cancha_id', $canchaIds);
        })
            ->where('estado', 'pagado')
            ->whereMonth('pagado_en', $hoy->month)
            ->whereYear('pagado_en', $hoy->year)
            ->sum('monto');

        // Pagos pendientes por cobrar hoy
        $pagosPendientesHoy = Pago::whereHas('reserva', function ($q) use ($canchaIds, $hoy) {
            $q->whereIn('cancha_id', $canchaIds)
                ->whereDate('inicio', $hoy)
                ->where('estado', 'confirmada');
        })
            ->where('estado', 'pendiente')
            ->sum('monto');

        return view('propietario.dashboard', compact(
            'establecimientos',
            'reservasHoy',
            'proximasReservas',
            'totalReservasMes',
            'ingresosMes',
            'pagosPendientesHoy'
        ));
    }
}
