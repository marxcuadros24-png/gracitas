<x-layouts.app title="Reportes Generales del Sistema">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Encabezado -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Reportes Generales del Sistema (RF-04)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Consolidado analítico de actividad, recaudación e impacto deportivo en Coracora, Ayacucho
            </p>
        </div>
        <button type="button" class="btn btn-outline btn-sm no-print" onclick="window.print()">
            <x-icon name="printer" /> Imprimir Reporte
        </button>
    </div>

    <!-- Filtro de Periodo -->
    <div class="card no-print" style="margin-bottom: 24px; padding: 18px;">
        <form action="{{ route('admin.reportes.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div>
                <label class="form-label">Desde:</label>
                <input type="date" name="desde" value="{{ $desde }}" class="form-control">
            </div>

            <div>
                <label class="form-label">Hasta:</label>
                <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                <x-icon name="search" /> Filtrar Periodo
            </button>
        </form>
    </div>

    <!-- Métricas del Periodo -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="border-left: 4px solid #10b981;">
            <span style="font-size: 0.8rem; color: #065f46; text-transform: uppercase; font-weight: 700;">Recaudación Global</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #065f46; margin-top: 4px;">
                S/. {{ number_format($totalIngresosGlobal, 2) }}
            </div>
            <small style="color: var(--text-muted);">Pagos presenciales confirmados</small>
        </div>

        <div class="card" style="border-left: 4px solid var(--primary);">
            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Reservas Realizadas</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 4px;">
                {{ $totalReservasGlobal }}
            </div>
            <small style="color: var(--text-muted);">En todos los complejos</small>
        </div>

        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <span style="font-size: 0.8rem; color: #5b21b6; text-transform: uppercase; font-weight: 700;">Usuarios Registrados</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #5b21b6; margin-top: 4px;">
                {{ $usuariosPorRol->sum('total') }}
            </div>
            <small style="color: var(--text-muted);">Comunidad deportiva activa</small>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
        <!-- Distribución de Usuarios por Rol -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="users" /> Usuarios por Rol</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th style="text-align: right;">Total Cuentas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuariosPorRol as $u)
                        <tr>
                            <td>
                                <span class="badge badge-role-{{ $u->rol }}">
                                    {{ ucfirst($u->rol) }}
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 700; font-size: 1.05rem;">
                                {{ $u->total }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Métodos de Pago Global -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="dollar" /> Recaudación por Medio de Pago</h3>
            @if($ingresosPorMetodo->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.9rem;">No hay pagos en este rango de fechas.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Método</th>
                            <th>Transacciones</th>
                            <th style="text-align: right;">Total Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingresosPorMetodo as $im)
                            <tr>
                                <td><strong style="text-transform: uppercase;">{{ $im->metodo }}</strong></td>
                                <td>{{ $im->transacciones }} pago(s)</td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary);">
                                    S/. {{ number_format($im->total_monto, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Ranking de Establecimientos en Coracora -->
    <div class="card">
        <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="building" /> Actividad por Complejo Deportivo en Coracora</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Complejo Deportivo</th>
                        <th>Propietario</th>
                        <th>Dirección</th>
                        <th style="text-align: right;">Reservas en el Periodo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankingEstablecimientos as $ranking)
                        <tr>
                            <td><strong>{{ $ranking->nombre }}</strong></td>
                            <td>{{ $ranking->propietario->name }}</td>
                            <td>{{ $ranking->direccion }}</td>
                            <td style="text-align: right; font-weight: 800; font-size: 1.1rem; color: var(--primary);">
                                {{ $ranking->reservas_count }} reservas
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media (max-width: 850px) {
    .container > div:nth-child(3) {
        grid-template-columns: 1fr !important;
    }
}
@media print {
    .no-print, .navbar, .footer, .demo-bar, .alert-wrap {
        display: none !important;
    }
    body { background: white !important; }
}
</style>
</x-layouts.app>
