<x-layouts.app title="Supervisión de Establecimientos">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Submenú de Supervisión -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Supervisión: Establecimientos Deportivos (RF-03)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Control y fiscalización de complejos deportivos en Coracora, Ayacucho
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.supervision.establecimientos') }}" class="btn btn-sm btn-primary">Establecimientos</a>
            <a href="{{ route('admin.supervision.canchas') }}" class="btn btn-sm btn-outline">Canchas</a>
            <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-sm btn-outline">Reservas</a>
            <a href="{{ route('admin.supervision.pagos') }}" class="btn btn-sm btn-outline">Pagos</a>
        </div>
    </div>

    <!-- Filtro de Propietario -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('admin.supervision.establecimientos') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label class="form-label">Filtrar por Propietario:</label>
                <select name="propietario_id" class="form-select">
                    <option value="">Todos los propietarios</option>
                    @foreach($propietarios as $p)
                        <option value="{{ $p->id }}" {{ request('propietario_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Filtrar</button>
            @if(request('propietario_id'))
                <a href="{{ route('admin.supervision.establecimientos') }}" class="btn btn-outline" style="padding: 10px 14px;">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Complejo</th>
                        <th>Propietario Responsable</th>
                        <th>Dirección en Coracora</th>
                        <th>Contacto</th>
                        <th>Canchas</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($establecimientos as $est)
                        <tr>
                            <td>
                                <strong>{{ $est->nombre }}</strong>
                                @if($est->descripcion)
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">{{ Str::limit($est->descripcion, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $est->propietario->name }}</strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $est->propietario->email }}</div>
                            </td>
                            <td>
                                <div>{{ $est->direccion }}</div>
                                @if($est->referencia)
                                    <small style="color: var(--text-muted);">Ref: {{ $est->referencia }}</small>
                                @endif
                            </td>
                            <td>{{ $est->telefono ?: 'No registrado' }}</td>
                            <td>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                    {{ $est->canchas->count() }} cancha(s)
                                </span>
                            </td>
                            <td>
                                @if($est->activo)
                                    <span class="badge" style="background: #ecfdf5; color: #065f46;">Activo</span>
                                @else
                                    <span class="badge" style="background: #fef2f2; color: #991b1b;">Inactivo</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('canchas.establecimiento', $est->id) }}" class="btn btn-outline btn-sm" target="_blank">
                                    Ver Público
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 16px;">
            {{ $establecimientos->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
