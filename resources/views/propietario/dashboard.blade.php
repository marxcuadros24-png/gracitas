<x-layouts.app title="Dashboard del Propietario">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Encabezado -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Panel de Control - Propietario</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Administración de canchas, control de partidos de hoy y registro de pagos presenciales en Coracora
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('propietario.reservas.manual') }}" class="btn btn-primary">
                <x-icon name="phone" /> Nueva Reserva Manual (Llamada / Presencial)
            </a>
            <a href="{{ route('propietario.canchas.create') }}" class="btn btn-outline">
                <x-icon name="plus" /> Añadir Cancha
            </a>
        </div>
    </div>

    <!-- KPIs de Hoy y del Mes -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="border-left: 4px solid var(--primary);">
            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Partidos Hoy</span>
            <div style="font-size: 2rem; font-weight: 800; color: #0f172a; margin-top: 4px;">
                {{ $reservasHoy->count() }}
            </div>
            <small style="color: var(--text-muted);">Programados para la fecha</small>
        </div>

        <div class="card" style="border-left: 4px solid #f59e0b;">
            <span style="font-size: 0.8rem; color: #92400e; text-transform: uppercase; font-weight: 700;">Por Cobrar Hoy</span>
            <div style="font-size: 2rem; font-weight: 800; color: #92400e; margin-top: 4px;">
                S/. {{ number_format($pagosPendientesHoy, 2) }}
            </div>
            <small style="color: var(--text-muted);">Pagos presenciales pendientes</small>
        </div>

        <div class="card" style="border-left: 4px solid #10b981;">
            <span style="font-size: 0.8rem; color: #065f46; text-transform: uppercase; font-weight: 700;">Ingresos Cobrados (Mes)</span>
            <div style="font-size: 2rem; font-weight: 800; color: #065f46; margin-top: 4px;">
                S/. {{ number_format($ingresosMes, 2) }}
            </div>
            <small style="color: var(--text-muted);">Mes en curso</small>
        </div>

        <div class="card" style="border-left: 4px solid #6366f1;">
            <span style="font-size: 0.8rem; color: #4338ca; text-transform: uppercase; font-weight: 700;">Total Reservas (Mes)</span>
            <div style="font-size: 2rem; font-weight: 800; color: #4338ca; margin-top: 4px;">
                {{ $totalReservasMes }}
            </div>
            <small style="color: var(--text-muted);">Confirmadas o completadas</small>
        </div>
    </div>

    <!-- Partidos y Reservas de Hoy -->
    <div class="card" style="margin-bottom: 30px; padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem;"><x-icon name="ball" /> Partidos Programados para Hoy ({{ \Carbon\Carbon::today('America/Lima')->format('d/m/Y') }})</h3>
            <span class="badge" style="background: var(--primary-light); color: var(--primary);">
                Tiempo Real
            </span>
        </div>

        @if($reservasHoy->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <p>No hay partidos registrados para el día de hoy.</p>
                <a href="{{ route('propietario.reservas.manual') }}" class="btn btn-outline-primary btn-sm" style="margin-top: 12px;">
                    Registrar reserva manual
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Horario</th>
                            <th>Cancha</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Estado Turno</th>
                            <th>Pago Presencial</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasHoy as $res)
                            <tr>
                                <td>
                                    <strong style="font-size: 1.05rem; color: var(--primary);">
                                        {{ $res->inicio->format('H:i') }} - {{ $res->fin->format('H:i') }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $res->cancha->nombre }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        {{ $res->cancha->establecimiento->nombre }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $res->cliente->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        <x-icon name="phone" size="13" /> {{ $res->cliente->telefono ?: 'Sin teléfono' }}
                                    </div>
                                </td>
                                <td>
                                    <strong>S/. {{ number_format($res->precio_total, 2) }}</strong>
                                </td>
                                <td>
                                    @php $badge = $res->estado_badge; @endphp
                                    <span class="badge {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($res->pago)
                                        @php $pBadge = $res->pago->estado_badge; @endphp
                                        <span class="badge {{ $pBadge['class'] }}">
                                            {{ $pBadge['label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        @if($res->pago && $res->pago->estado === 'pendiente')
                                            <a href="{{ route('propietario.pagos.index') }}" class="btn btn-sm btn-primary" title="Cobrar">
                                                <x-icon name="dollar" /> Cobrar
                                            </a>
                                        @endif
                                        <a href="{{ route('cliente.reserva.comprobante', $res->codigo) }}" class="btn btn-sm btn-outline" target="_blank">
                                            Ticket
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Próximas reservas -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.2rem;"><x-icon name="calendar" /> Próximas Reservas de los Siguientes Días</h3>
            <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline btn-sm">Ver todas las reservas</a>
        </div>

        @if($proximasReservas->isEmpty())
            <div style="text-align: center; padding: 30px; color: var(--text-muted);">
                No hay reservas futuras confirmadas en los próximos días.
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Cancha</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Pago</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proximasReservas as $res)
                            <tr>
                                <td>{{ $res->inicio->format('d/m/Y') }}</td>
                                <td><strong>{{ $res->inicio->format('H:i') }} - {{ $res->fin->format('H:i') }}</strong></td>
                                <td>{{ $res->cancha->nombre }}</td>
                                <td>{{ $res->cliente->name }}</td>
                                <td>S/. {{ number_format($res->precio_total, 2) }}</td>
                                <td>
                                    @if($res->pago)
                                        <span class="badge {{ $res->pago->estado_badge['class'] }}">
                                            {{ $res->pago->estado_badge['label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('cliente.reserva.comprobante', $res->codigo) }}" class="btn btn-sm btn-outline">
                                        Detalles
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
