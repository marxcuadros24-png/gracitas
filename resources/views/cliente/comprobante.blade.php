<x-layouts.app :title="'Comprobante de Reserva - ' . $reserva->codigo">
<div class="container" style="max-width: 680px; padding-top: 30px; padding-bottom: 60px;">
    <!-- Acciones superiores -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="no-print">
        @if(auth()->user()->isCliente())
            <a href="{{ route('cliente.reservas') }}" class="btn btn-outline btn-sm">
                &larr; Volver a Mis Reservas
            </a>
        @elseif(auth()->user()->isPropietario())
            <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline btn-sm">
                &larr; Volver a Reservas
            </a>
        @else
            <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-outline btn-sm">
                &larr; Volver a Supervisión
            </a>
        @endif

        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
            <x-icon name="printer" /> Imprimir / Guardar PDF
        </button>
    </div>

    <!-- Ticket Oficial GraCitas -->
    <div class="card comprobante-card" style="border: 2px solid var(--border); padding: 32px; background: white;">
        <!-- Cabecera del Voucher -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed var(--border); padding-bottom: 20px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ asset('images/logo.svg') }}" alt="GraCitas" style="height: 56px;">
                <div>
                    <h2 style="font-size: 1.6rem; color: #0f172a; line-height: 1.1;">GraCitas</h2>
                    <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                        Comprobante Digital de Reserva
                    </span>
                </div>
            </div>

            <div style="text-align: right;">
                <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; display: block;">Código Único</span>
                <span style="font-family: monospace; font-size: 1.3rem; font-weight: 800; color: var(--primary);">
                    {{ $reserva->codigo }}
                </span>
            </div>
        </div>

        <!-- Estado de la Reserva -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px; border: 1px solid #f1f5f9;">
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Estado del Turno</span>
                @php $badge = $reserva->estado_badge; @endphp
                <div style="margin-top: 4px;">
                    <span class="badge {{ $badge['class'] }}" style="font-size: 0.85rem; padding: 5px 12px;">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>

            <div style="text-align: right;">
                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Estado del Pago</span>
                @if($reserva->pago)
                    @php $pBadge = $reserva->pago->estado_badge; @endphp
                    <div style="margin-top: 4px;">
                        <span class="badge {{ $pBadge['class'] }}" style="font-size: 0.85rem; padding: 5px 12px;">
                            {{ $pBadge['label'] }} ({{ ucfirst($reserva->pago->metodo) }})
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Datos del Cliente y Cancha -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div>
                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">
                    Información del Cliente
                </h4>
                <div style="font-weight: 700; font-size: 1.05rem; color: #0f172a;">{{ $reserva->cliente->name }}</div>
                <div style="font-size: 0.85rem; color: #475569;"><x-icon name="mail" size="13" /> {{ $reserva->cliente->email }}</div>
                @if($reserva->cliente->telefono)
                    <div style="font-size: 0.85rem; color: #475569;"><x-icon name="phone" size="13" /> {{ $reserva->cliente->telefono }}</div>
                @endif
            </div>

            <div>
                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">
                    Establecimiento Deportivo
                </h4>
                <div style="font-weight: 700; font-size: 1.05rem; color: #0f172a;">{{ $reserva->cancha->establecimiento->nombre }}</div>
                <div style="font-size: 0.85rem; color: #475569;"><x-icon name="map-pin" size="13" /> {{ $reserva->cancha->establecimiento->direccion }}</div>
                @if($reserva->cancha->establecimiento->referencia)
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Ref: {{ $reserva->cancha->establecimiento->referencia }}</div>
                @endif
                @if($reserva->cancha->establecimiento->telefono)
                    <div style="font-size: 0.85rem; color: #475569;"><x-icon name="phone" size="13" /> Tel: {{ $reserva->cancha->establecimiento->telefono }}</div>
                @endif
            </div>
        </div>

        <!-- Detalles del Partido -->
        <div style="border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 24px;">
            <table class="table" style="margin: 0;">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th>Cancha</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $reserva->cancha->nombre }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                S/. {{ number_format($reserva->precio_hora, 2) }} / hr
                            </div>
                        </td>
                        <td>
                            <strong>{{ $reserva->inicio->format('d/m/Y') }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                {{ \Carbon\Carbon::parse($reserva->inicio)->locale('es')->isoFormat('dddd') }}
                            </div>
                        </td>
                        <td>
                            <strong>{{ $reserva->inicio->format('H:i') }} - {{ $reserva->fin->format('H:i') }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                ({{ $reserva->inicio->diffInMinutes($reserva->fin) }} minutos)
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <strong style="font-size: 1.15rem; color: var(--primary);">
                                S/. {{ number_format($reserva->precio_total, 2) }}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($reserva->observaciones)
            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; color: #475569; margin-bottom: 20px;">
                <strong>Observaciones:</strong> {{ $reserva->observaciones }}
            </div>
        @endif

        @if($reserva->estado === 'cancelada')
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; color: #991b1b; margin-bottom: 20px;">
                <strong>Motivo de Cancelación:</strong> {{ $reserva->motivo_cancelacion }}
                <div style="font-size: 0.75rem; margin-top: 4px;">Cancelada en: {{ $reserva->cancelada_en ? $reserva->cancelada_en->format('d/m/Y H:i') : '-' }}</div>
            </div>
        @endif

        <!-- Instrucciones y Reglas de Coracora -->
        <div style="border-top: 2px dashed var(--border); padding-top: 18px; font-size: 0.8rem; color: #64748b; line-height: 1.6;">
            <strong>Reglas del Establecimiento:</strong>
            <ul style="margin-left: 20px; margin-top: 6px;">
                <li>Presentarse en el establecimiento 10 minutos antes del inicio del turno.</li>
                <li>El pago se efectúa de manera presencial en recepción al momento del ingreso.</li>
                <li>Uso obligatorio de zapatillas de fútbol para césped sintético (multitaco / suela plana). Prohibido calzado con toperoles de metal.</li>
                <li>En caso de reprogramación o cancelación, notificar con un mínimo de 2 horas de anticipación.</li>
            </ul>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, .navbar, .footer, .demo-bar, .alert-wrap {
        display: none !important;
    }
    body {
        background: white !important;
    }
    .container {
        padding: 0 !important;
        max-width: 100% !important;
    }
    .comprobante-card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
</x-layouts.app>
