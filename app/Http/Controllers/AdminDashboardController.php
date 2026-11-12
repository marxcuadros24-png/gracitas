<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today('America/Lima');

        $totalUsuarios = User::count();
        $totalClientes = User::where('rol', 'cliente')->count();
        $totalPropietarios = User::where('rol', 'propietario')->count();
        $totalEstablecimientos = Establecimiento::count();
        $totalCanchas = Cancha::count();
        $totalReservas = Reserva::count();

        $reservasHoy = Reserva::whereDate('inicio', $hoy)->count();
        $totalIngresosGlobal = Pago::where('estado', 'pagado')->sum('monto');

        $ultimasReservas = Reserva::with(['cliente', 'cancha.establecimiento', 'pago'])
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        $ultimosUsuarios = User::orderBy('id', 'desc')->limit(6)->get();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalClientes',
            'totalPropietarios',
            'totalEstablecimientos',
            'totalCanchas',
            'totalReservas',
            'reservasHoy',
            'totalIngresosGlobal',
            'ultimasReservas',
            'ultimosUsuarios'
        ));
    }
}
