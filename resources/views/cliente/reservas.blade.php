<x-layouts.app title="Mis Reservas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Mis Reservas de Canchas</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Consulta tu historial de reservas, comprobantes y estado de pagos</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <x-icon name="plus" /> Nueva Reserva
        </a>
    </div>

    <!-- Filtros de Estado -->
    <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
        <a href="{{ route('cliente.reservas') }}" class="btn btn-sm {{ !request('estado') ? 'btn-primary' : 'btn-outline' }}">
            Todas
        </a>
        <a href="{{ route('cliente.reservas', ['estado' => 'confirmada']) }}" class="btn btn-sm {{ request('estado') === 'confirmada' ? 'btn-primary' : 'btn-outline' }}">
            Confirmadas
        </a>
        <a href="{{ route('cliente.reservas', ['estado' => 'completada']) }}" class="btn btn-sm {{ request('estado') === 'completada' ? 'btn-primary' : 'btn-outline' }}">
            Completadas
        </a>
        <a href="{{ route('cliente.reservas', ['estado' => 'cancelada']) }}" class="btn btn-sm {{ request('estado') === 'cancelada' ? 'btn-primary' : 'btn-outline' }}">
            Canceladas
        </a>
    </div>

    @if($reservas->isEmpty())
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <div style="margin-bottom: 12px;"><x-icon name="ball" size="48" style="color: var(--text-muted);" /></div>
            <h3>No tienes reservas registradas</h3>
            <p style="color: var(--text-muted); margin-top: 6px;">¡Explora los mejores complejos de Coracora y organiza tu partido hoy mismo!</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 20px;">
                Explorar Canchas en Coracora
            </a>
        </div>
    @else
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Complejo / Cancha</th>
                            <th>Fecha y Horario</th>
                            <th>Monto Total</th>
                            <th>Estado Reserva</th>
                            <th>Pago Presencial</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary); font-family: monospace; font-size: 0.95rem;">
                                        {{ $reserva->codigo }}
                                    </strong>
                                </td>
                                <td>
                                    <strong>{{ $reserva->cancha->establecimiento->nombre }}</strong>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        {{ $reserva->cancha->nombre }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">
                                        {{ $reserva->inicio->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        {{ $reserva->inicio->format('H:i') }} - {{ $reserva->fin->format('H:i') }}
                                        ({{ $reserva->inicio->diffInMinutes($reserva->fin) }} min)
                                    </div>
                                </td>
                                <td>
                                    <strong style="font-size: 1rem; color: #0f172a;">
                                        S/. {{ number_format($reserva->precio_total, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @php $badge = $reserva->estado_badge; @endphp
                                    <span class="badge {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($reserva->pago)
                                        @php $pBadge = $reserva->pago->estado_badge; @endphp
                                        <span class="badge {{ $pBadge['class'] }}">
                                            {{ $pBadge['label'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-gray-100 text-gray-800">No registrado</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="{{ route('cliente.reserva.comprobante', $reserva->codigo) }}" class="btn btn-outline btn-sm">
                                            <x-icon name="file-text" /> Comprobante
                                        </a>

                                        @if($reserva->puedeCancelarPorCliente())
                                            <button type="button" class="btn btn-danger btn-sm" onclick="abrirModalCancelar({{ $reserva->id }}, '{{ $reserva->codigo }}')">
                                                Cancelar
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 24px;">
            {{ $reservas->links() }}
        </div>
    @endif
</div>

<!-- Modal para Cancelar Reserva -->
<div id="modal-cancelar" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; padding: 16px;">
    <div class="card" style="max-width: 480px; width: 100%; animation: slideDown 0.2s ease;">
        <h3 style="margin-bottom: 10px; color: #991b1b;"><x-icon name="warning" /> Cancelar Reserva</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px;">
            ¿Estás seguro de cancelar la reserva <strong id="modal-codigo"></strong>? Según las reglas de negocio, la cancelación se efectúa liberando la franja horaria inmediatamente.
        </p>

        <form id="form-cancelar" method="POST">
            @csrf
            <div class="form-group">
                <label for="motivo_cancelacion" class="form-label">Motivo de la cancelación:</label>
                <textarea name="motivo_cancelacion" id="motivo_cancelacion" rows="3" class="form-control" required placeholder="Indica brevemente el motivo..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn btn-outline" onclick="cerrarModalCancelar()">Cerrar</button>
                <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalCancelar(id, codigo) {
        document.getElementById('modal-codigo').innerText = codigo;
        document.getElementById('form-cancelar').action = '/cliente/reserva/' + id + '/cancelar';
        document.getElementById('modal-cancelar').style.display = 'flex';
    }

    function cerrarModalCancelar() {
        document.getElementById('modal-cancelar').style.display = 'none';
    }
</script>
</x-layouts.app>
