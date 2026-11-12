<x-layouts.app title="Gestión de Reservas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Control de Reservas de Canchas</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Supervisa y gestiona las reservas confirmadas, completadas o canceladas de tus establecimientos
            </p>
        </div>
        <a href="{{ route('propietario.reservas.manual') }}" class="btn btn-primary">
            <x-icon name="phone" /> Registrar Reserva Manual (Llamada / Presencial)
        </a>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('propietario.reservas.index') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 180px;">
                <label class="form-label">Filtrar por Cancha:</label>
                <select name="cancha_id" class="form-select">
                    <option value="">Todas las canchas</option>
                    @foreach($canchas as $c)
                        <option value="{{ $c->id }}" {{ request('cancha_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->establecimiento->nombre }} &bull; {{ $c->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="min-width: 160px;">
                <label class="form-label">Filtrar por Fecha:</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
            </div>

            <div style="min-width: 160px;">
                <label class="form-label">Estado de la Reserva:</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="no_asistio" {{ request('estado') === 'no_asistio' ? 'selected' : '' }}>No Asistió</option>
                    <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
                Filtrar
            </button>
            @if(request()->anyFilled(['cancha_id', 'fecha', 'estado']))
                <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline" style="padding: 10px 14px;">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla de Reservas -->
    @if($reservas->isEmpty())
        <div class="card" style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
            <h3>No se encontraron reservas con los filtros seleccionados</h3>
            <p style="margin-top: 6px;">Intenta ajustando los parámetros de búsqueda o registra una nueva reserva manual.</p>
        </div>
    @else
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cancha / Complejo</th>
                            <th>Cliente</th>
                            <th>Fecha y Hora</th>
                            <th>Monto</th>
                            <th>Estado</th>
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
                                <td>
                                    <strong>{{ $res->cancha->nombre }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        {{ $res->cancha->establecimiento->nombre }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $res->cliente->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        <x-icon name="phone" size="13" /> {{ $res->cliente->telefono ?: 'Sin cel' }}
                                    </div>
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
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="abrirModalEstado({{ $res->id }}, '{{ $res->codigo }}', '{{ $res->estado }}')">
                                            <x-icon name="settings" /> Estado
                                        </button>
                                        <a href="{{ route('cliente.reserva.comprobante', $res->codigo) }}" class="btn btn-outline btn-sm" target="_blank" title="Ver Comprobante">
                                            <x-icon name="file-text" />
                                        </a>
                                    </div>
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
    @endif
</div>

<!-- Modal para Cambio de Estado de Reserva -->
<div id="modal-estado" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; padding: 16px;">
    <div class="card" style="max-width: 480px; width: 100%;">
        <h3 style="margin-bottom: 12px;">Actualizar Estado de la Reserva</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px;">
            Reserva: <strong id="modal-estado-codigo"></strong>
        </p>

        <form id="form-estado" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="select-nuevo-estado" class="form-label">Seleccionar Nuevo Estado:</label>
                <select name="estado" id="select-nuevo-estado" class="form-select" onchange="toggleMotivo(this.value)" required>
                    <option value="confirmada">Confirmada (Vigente)</option>
                    <option value="completada">Completada (Partido jugado con éxito)</option>
                    <option value="no_asistio">No Asistió (El cliente no se presentó)</option>
                    <option value="cancelada">Cancelada (Liberar horario)</option>
                </select>
            </div>

            <div class="form-group" id="wrap-motivo" style="display: none;">
                <label for="motivo_cancelacion" class="form-label">Motivo de Cancelación:</label>
                <input type="text" name="motivo_cancelacion" id="motivo_cancelacion" class="form-control" placeholder="Indica el motivo de anulación...">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="cerrarModalEstado()">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEstado(id, codigo, estadoActual) {
        document.getElementById('modal-estado-codigo').innerText = codigo;
        document.getElementById('select-nuevo-estado').value = estadoActual;
        document.getElementById('form-estado').action = '/propietario/reservas/' + id + '/estado';
        toggleMotivo(estadoActual);
        document.getElementById('modal-estado').style.display = 'flex';
    }

    function cerrarModalEstado() {
        document.getElementById('modal-estado').style.display = 'none';
    }

    function toggleMotivo(val) {
        document.getElementById('wrap-motivo').style.display = (val === 'cancelada') ? 'block' : 'none';
    }
</script>
</x-layouts.app>
