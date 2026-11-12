<x-layouts.app title="Supervisión de Reservas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Submenú de Supervisión -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Supervisión: Reservas Globales (RF-03)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Historial completo de reservas realizadas en la plataforma GraCitas
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.supervision.establecimientos') }}" class="btn btn-sm btn-outline">Establecimientos</a>
            <a href="{{ route('admin.supervision.canchas') }}" class="btn btn-sm btn-outline">Canchas</a>
            <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-sm btn-primary">Reservas</a>
            <a href="{{ route('admin.supervision.pagos') }}" class="btn btn-sm btn-outline">Pagos</a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('admin.supervision.reservas') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
            <div style="min-width: 160px;">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
            </div>

            <div style="min-width: 160px;">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="no_asistio" {{ request('estado') === 'no_asistio' ? 'selected' : '' }}>No Asistió</option>
                    <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Filtrar</button>
            @if(request()->anyFilled(['fecha', 'estado']))
                <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-outline" style="padding: 10px 14px;">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Complejo Deportivo</th>
                        <th>Cancha</th>
                        <th>Cliente</th>
                        <th>Fecha y Horario</th>
                        <th>Monto</th>
                        <th>Estado Turno</th>
                        <th>Pago Presencial</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $res)
                        <tr>
                            <td>
                                <strong style="color: var(--primary); font-family: monospace;">{{ $res->codigo }}</strong>
                            </td>
                            <td>{{ $res->cancha->establecimiento->nombre }}</td>
                            <td>{{ $res->cancha->nombre }}</td>
                            <td>
                                <strong>{{ $res->cliente->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $res->cliente->email }}</div>
                            </td>
                            <td>
                                <strong>{{ $res->inicio->format('d/m/Y') }}</strong>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $res->inicio->format('H:i') }} - {{ $res->fin->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <strong>S/. {{ number_format($res->precio_total, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $res->estado_badge['class'] }}">
                                    {{ $res->estado_badge['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($res->pago)
                                    <span class="badge {{ $res->pago->estado_badge['class'] }}">
                                        {{ $res->pago->estado_badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('cliente.reserva.comprobante', $res->codigo) }}" class="btn btn-outline btn-sm" target="_blank">
                                    Ver Ticket
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 16px;">
            {{ $reservas->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
