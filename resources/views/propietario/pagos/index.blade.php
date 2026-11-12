<x-layouts.app title="Gestión de Pagos Presenciales">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Caja y Pagos Presenciales (RF-14)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Control de cobros presenciales realizados en ventanilla del establecimiento (Efectivo, Yape, Plin)
            </p>
        </div>
        <a href="{{ route('propietario.reportes.index') }}" class="btn btn-outline">
            <x-icon name="chart" /> Ver Reportes de Ingresos
        </a>
    </div>

    <!-- Filtros -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('propietario.pagos.index') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
            <div style="min-width: 180px;">
                <label class="form-label">Estado del Pago:</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente Presencial</option>
                    <option value="pagado" {{ request('estado') === 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="anulado" {{ request('estado') === 'anulado' ? 'selected' : '' }}>Anulado</option>
                </select>
            </div>

            <div style="min-width: 180px;">
                <label class="form-label">Método de Pago:</label>
                <select name="metodo" class="form-select">
                    <option value="">Todos los métodos</option>
                    <option value="efectivo" {{ request('metodo') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="yape" {{ request('metodo') === 'yape' ? 'selected' : '' }}>Yape</option>
                    <option value="plin" {{ request('metodo') === 'plin' ? 'selected' : '' }}>Plin</option>
                    <option value="otro" {{ request('metodo') === 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
                Filtrar
            </button>
            @if(request()->anyFilled(['estado', 'metodo']))
                <a href="{{ route('propietario.pagos.index') }}" class="btn btn-outline" style="padding: 10px 14px;">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla de Pagos -->
    @if($pagos->isEmpty())
        <div class="card" style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
            <h3>No hay registros de pagos para mostrar</h3>
        </div>
    @else
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Reserva</th>
                            <th>Cancha / Local</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado Pago</th>
                            <th>Fecha de Pago</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagos as $p)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary); font-family: monospace;">
                                        {{ $p->reserva->codigo }}
                                    </strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        Turno: {{ $p->reserva->inicio->format('d/m H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $p->reserva->cancha->nombre }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        {{ $p->reserva->cancha->establecimiento->nombre }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $p->reserva->cliente->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        <x-icon name="phone" size="13" /> {{ $p->reserva->cliente->telefono ?: 'Sin cel' }}
                                    </div>
                                </td>
                                <td>
                                    <strong style="font-size: 1.05rem; color: #0f172a;">
                                        S/. {{ number_format($p->monto, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <span style="font-weight: 600; text-transform: uppercase; font-size: 0.8rem;">
                                        {{ $p->metodo }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $p->estado_badge['class'] }}">
                                        {{ $p->estado_badge['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($p->pagado_en)
                                        <div style="font-size: 0.85rem; font-weight: 600;">
                                            {{ $p->pagado_en->format('d/m/Y H:i') }}
                                        </div>
                                        @if($p->registradoPor)
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                Por: {{ $p->registradoPor->name }}
                                            </div>
                                        @endif
                                    @else
                                        <span style="color: #92400e; font-size: 0.85rem;">Pendiente de recepción</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        @if($p->estado === 'pendiente')
                                            <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalCobro({{ $p->id }}, '{{ $p->reserva->codigo }}', {{ $p->monto }}, '{{ $p->metodo }}')">
                                                <x-icon name="dollar" /> Registrar Cobro
                                            </button>
                                        @endif
                                        <a href="{{ route('cliente.reserva.comprobante', $p->reserva->codigo) }}" class="btn btn-outline btn-sm" target="_blank" title="Ver Comprobante">
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
                {{ $pagos->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal para Registrar Pago Presencial -->
<div id="modal-cobro" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; padding: 16px;">
    <div class="card" style="max-width: 480px; width: 100%;">
        <h3 style="margin-bottom: 8px;"><x-icon name="dollar" /> Registrar Pago Presencial</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 18px;">
            Reserva: <strong id="modal-cobro-codigo"></strong>
        </p>

        <form id="form-cobro" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="monto" class="form-label">Monto a Cobrar (S/.)</label>
                <input type="number" step="0.50" name="monto" id="modal-monto" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="metodo" class="form-label">Medio de Pago Recibido:</label>
                <select name="metodo" id="modal-metodo" class="form-select" required>
                    <option value="efectivo">Efectivo en Ventanilla</option>
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                    <option value="otro">Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="observacion" class="form-label">Observación / Nro. Operación (Opcional):</label>
                <input type="text" name="observacion" id="observacion" class="form-control" placeholder="Ej. Yape recibido por S/. 60, op #485910">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="cerrarModalCobro()">Cancelar</button>
                <button type="submit" class="btn btn-primary"><x-icon name="check" /> Confirmar Cobro Recibido</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalCobro(id, codigo, monto, metodo) {
        document.getElementById('modal-cobro-codigo').innerText = codigo;
        document.getElementById('modal-monto').value = monto;
        document.getElementById('modal-metodo').value = metodo;
        document.getElementById('form-cobro').action = '/propietario/pagos/' + id + '/registrar';
        document.getElementById('modal-cobro').style.display = 'flex';
    }

    function cerrarModalCobro() {
        document.getElementById('modal-cobro').style.display = 'none';
    }
</script>
</x-layouts.app>
