<x-layouts.app title="Reportes de Ingresos y Reservas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Encabezado -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Reportes de Ingresos y Reservas (RF-15)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Estadísticas de recaudación, volumen de partidos y métodos de pago de tus establecimientos
            </p>
        </div>
        <button type="button" class="btn btn-outline btn-sm no-print" onclick="window.print()">
            <x-icon name="printer" /> Imprimir Reporte
        </button>
    </div>

    <!-- Filtro de Periodo -->
    <div class="card no-print" style="margin-bottom: 24px; padding: 18px;">
        <form action="{{ route('propietario.reportes.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
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

    <!-- Tarjetas de Métricas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="border-left: 4px solid #10b981;">
            <span style="font-size: 0.8rem; color: #065f46; text-transform: uppercase; font-weight: 700;">Ingresos Cobrados</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #065f46; margin-top: 4px;">
                S/. {{ number_format($totalIngresos, 2) }}
            </div>
            <small style="color: var(--text-muted);">Pagos presenciales confirmados</small>
        </div>

        <div class="card" style="border-left: 4px solid #f59e0b;">
            <span style="font-size: 0.8rem; color: #92400e; text-transform: uppercase; font-weight: 700;">Pendiente de Cobro</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #92400e; margin-top: 4px;">
                S/. {{ number_format($totalPendiente, 2) }}
            </div>
            <small style="color: var(--text-muted);">En reservas activas</small>
        </div>

        <div class="card" style="border-left: 4px solid var(--primary);">
            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Reservas</span>
            <div style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 4px;">
                {{ $totalReservas }}
            </div>
            <small style="color: var(--text-muted);">Turnos solicitados en el rango</small>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
        <!-- Ingresos por Método de Pago -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="dollar" /> Recaudación por Método de Pago</h3>
            @if($ingresosPorMetodo->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.9rem;">No hay pagos registrados en este periodo.</p>
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
                        @foreach($ingresosPorMetodo as $met)
                            <tr>
                                <td><strong style="text-transform: uppercase;">{{ $met->metodo }}</strong></td>
                                <td>{{ $met->transacciones }} pago(s)</td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary);">
                                    S/. {{ number_format($met->total_monto, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Reservas por Estado -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="chart" /> Distribución por Estado de Reserva</h3>
            @if($reservasPorEstado->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.9rem;">No hay reservas en este periodo.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th style="text-align: right;">Cantidad de Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasPorEstado as $est)
                            <tr>
                                <td>
                                    <strong style="text-transform: capitalize;">{{ $est->estado }}</strong>
                                </td>
                                <td style="text-align: right; font-weight: 700; font-size: 1.05rem;">
                                    {{ $est->total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Canchas más populares -->
    <div class="card">
        <h3 style="font-size: 1.15rem; margin-bottom: 16px;"><x-icon name="trophy" /> Rendimiento por Cancha Sintética</h3>
        @if($canchasPopulares->isEmpty())
            <p style="color: var(--text-muted); font-size: 0.9rem;">No hay actividad en las canchas durante este periodo.</p>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cancha</th>
                            <th>Complejo Deportivo</th>
                            <th>Partidos Reservados</th>
                            <th style="text-align: right;">Monto Total Generado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($canchasPopulares as $pop)
                            <tr>
                                <td><strong>{{ $pop->cancha->nombre }}</strong></td>
                                <td>{{ $pop->cancha->establecimiento->nombre }}</td>
                                <td><strong>{{ $pop->total_reservas }}</strong> partidos</td>
                                <td style="text-align: right; font-weight: 800; color: var(--primary); font-size: 1.05rem;">
                                    S/. {{ number_format($pop->total_generado, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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
