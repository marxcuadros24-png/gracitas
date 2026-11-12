<x-layouts.app title="Supervisión de Canchas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Submenú de Supervisión -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Supervisión: Canchas Sintéticas (RF-03)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Supervisión global de tarifas por hora, capacidades y estado de operatividad
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.supervision.establecimientos') }}" class="btn btn-sm btn-outline">Establecimientos</a>
            <a href="{{ route('admin.supervision.canchas') }}" class="btn btn-sm btn-primary">Canchas</a>
            <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-sm btn-outline">Reservas</a>
            <a href="{{ route('admin.supervision.pagos') }}" class="btn btn-sm btn-outline">Pagos</a>
        </div>
    </div>

    <!-- Filtro por Establecimiento -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('admin.supervision.canchas') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label class="form-label">Filtrar por Complejo Deportivo:</label>
                <select name="establecimiento_id" class="form-select">
                    <option value="">Todos los establecimientos</option>
                    @foreach($establecimientos as $est)
                        <option value="{{ $est->id }}" {{ request('establecimiento_id') == $est->id ? 'selected' : '' }}>
                            {{ $est->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Filtrar</button>
            @if(request('establecimiento_id'))
                <a href="{{ route('admin.supervision.canchas') }}" class="btn btn-outline" style="padding: 10px 14px;">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cancha</th>
                        <th>Establecimiento</th>
                        <th>Propietario</th>
                        <th>Capacidad</th>
                        <th>Tarifa / Hora</th>
                        <th>Intervalo</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($canchas as $c)
                        <tr>
                            <td><strong>{{ $c->nombre }}</strong></td>
                            <td>{{ $c->establecimiento->nombre }}</td>
                            <td>{{ $c->establecimiento->propietario->name }}</td>
                            <td><x-icon name="users" size="13" /> {{ $c->capacidad }} jugadores</td>
                            <td>
                                <strong style="color: var(--primary); font-size: 1.05rem;">
                                    S/. {{ number_format($c->precio_hora, 2) }}
                                </strong>
                            </td>
                            <td><x-icon name="clock" size="13" /> {{ $c->duracion_reserva_min }} min</td>
                            <td>
                                @if($c->activa)
                                    <span class="badge" style="background: #ecfdf5; color: #065f46;">Activa</span>
                                @else
                                    <span class="badge" style="background: #fef2f2; color: #991b1b;">Inactiva</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('canchas.detalle', $c->id) }}" class="btn btn-outline btn-sm" target="_blank">
                                    Ver Disponibilidad
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 16px;">
            {{ $canchas->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
