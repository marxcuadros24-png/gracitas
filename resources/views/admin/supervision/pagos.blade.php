<x-layouts.app title="Supervisión de Pagos">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Submenú de Supervisión -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Supervisión: Pagos Presenciales y Transacciones (RF-03)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Registro de cobros presenciales reportados por los propietarios en Coracora
            </p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.supervision.establecimientos') }}" class="btn btn-sm btn-outline">Establecimientos</a>
            <a href="{{ route('admin.supervision.canchas') }}" class="btn btn-sm btn-outline">Canchas</a>
            <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-sm btn-outline">Reservas</a>
            <a href="{{ route('admin.supervision.pagos') }}" class="btn btn-sm btn-primary">Pagos</a>
        </div>
    </div>

    <!-- Filtro -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('admin.supervision.pagos') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
            <div style="min-width: 180px;">
                <label class="form-label">Estado:</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagado" {{ request('estado') === 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="anulado" {{ request('estado') === 'anulado' ? 'selected' : '' }}>Anulado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">Filtrar</button>
            @if(request('estado'))
                <a href="{{ route('admin.supervision.pagos') }}" class="btn btn-outline" style="padding: 10px 14px;">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Establecimiento</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha de Pago</th>
                        <th>Registrado Por</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $p)
                        <tr>
                            <td>
                                <strong style="color: var(--primary); font-family: monospace;">{{ $p->reserva->codigo }}</strong>
                            </td>
                            <td>{{ $p->reserva->cancha->establecimiento->nombre }}</td>
                            <td>{{ $p->reserva->cliente->name }}</td>
                            <td>
                                <strong style="font-size: 1.05rem;">S/. {{ number_format($p->monto, 2) }}</strong>
                            </td>
                            <td><strong style="text-transform: uppercase;">{{ $p->metodo }}</strong></td>
                            <td>
                                <span class="badge {{ $p->estado_badge['class'] }}">
                                    {{ $p->estado_badge['label'] }}
                                </span>
                            </td>
                            <td>
                                {{ $p->pagado_en ? $p->pagado_en->format('d/m/Y H:i') : 'Pendiente' }}
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $p->registradoPor ? $p->registradoPor->name : 'En espera' }}
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
</div>
</x-layouts.app>
