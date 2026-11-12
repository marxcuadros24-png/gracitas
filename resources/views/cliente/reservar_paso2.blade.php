<x-layouts.app :title="'Confirmar Reserva - ' . $cancha->nombre">
<div class="container" style="max-width: 680px; padding-top: 30px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('home') }}">Inicio</a> &raquo;
        <a href="{{ route('canchas.detalle', $cancha->id) }}">{{ $cancha->nombre }}</a> &raquo;
        <span>Confirmar Reserva</span>
    </div>

    <div class="card" style="border-top: 4px solid var(--primary);">
        <div class="card-header">
            <div>
                <h2>Confirmación de Reserva</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Verifica los datos de tu partido antes de confirmar</p>
            </div>
            <span class="badge" style="background: var(--primary-light); color: var(--primary);">
                Paso Final
            </span>
        </div>

        <form action="{{ route('cliente.reservar.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">
            <input type="hidden" name="fecha" value="{{ $fecha }}">
            <input type="hidden" name="hora_inicio" value="{{ $hora }}">

            <!-- Tarjeta de Resumen -->
            <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.925rem;">
                    <div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Complejo Deportivo</span>
                        <div style="font-weight: 700; color: #0f172a;">{{ $cancha->establecimiento->nombre }}</div>
                        <small style="color: var(--text-muted);"><x-icon name="map-pin" size="13" /> {{ $cancha->establecimiento->direccion }}</small>
                    </div>

                    <div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Cancha Seleccionada</span>
                        <div style="font-weight: 700; color: #0f172a;">{{ $cancha->nombre }}</div>
                        <small style="color: var(--text-muted);"><x-icon name="users" size="13" /> {{ $cancha->capacidad }} jugadores recomendados</small>
                    </div>

                    <div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Fecha del Partido</span>
                        <div style="font-weight: 700; color: #0f172a;">
                            {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                        </div>
                    </div>

                    <div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Hora de Inicio</span>
                        <div style="font-weight: 800; color: var(--primary); font-size: 1.1rem;">
                            {{ $hora }} hrs
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selección de Duración -->
            <div class="form-group">
                <label for="duracion_min" class="form-label">Duración del Partido</label>
                <select name="duracion_min" id="duracion_min" class="form-select" onchange="recalcularTotal(this.value)">
                    <option value="60" {{ $duracion == 60 ? 'selected' : '' }}>1 Hora (60 minutos)</option>
                    <option value="120" {{ $duracion == 120 ? 'selected' : '' }}>2 Horas (120 minutos)</option>
                    <option value="180" {{ $duracion == 180 ? 'selected' : '' }}>3 Horas (180 minutos)</option>
                </select>
                @error('duracion_min')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Desglose de Pago -->
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: var(--radius-sm); padding: 18px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span>Precio por hora:</span>
                    <strong>S/. {{ number_format($cancha->precio_hora, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span>Duración seleccionada:</span>
                    <strong id="label-duracion">{{ $duracion / 60 }} hora(s)</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px dashed #6ee7b7; font-size: 1.25rem;">
                    <span style="font-weight: 800; color: #064e3b;">Total a pagar:</span>
                    <strong id="label-total" style="color: #064e3b; font-size: 1.4rem;">
                        S/. {{ number_format(($duracion / 60) * $cancha->precio_hora, 2) }}
                    </strong>
                </div>
                <div style="font-size: 0.8rem; color: #047857; margin-top: 8px;">
                    <x-icon name="dollar" size="14" /> Modalidad: Pago presencial en recepción al momento de ingresar a jugar (Efectivo / Yape / Plin).
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones o notas para el establecimiento (Opcional)</label>
                <textarea name="observaciones" id="observaciones" rows="2" class="form-control" placeholder="Ej. Llevaremos chalecos propios, solicitar pelota reglamentaria, etc."></textarea>
                @error('observaciones')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 14px; margin-top: 24px;">
                <a href="{{ route('canchas.detalle', ['cancha' => $cancha->id, 'fecha' => $fecha]) }}" class="btn btn-outline" style="flex: 1;">
                    &larr; Volver a Horarios
                </a>
                <button type="submit" class="btn btn-primary" style="flex: 2; padding: 14px;">
                    <x-icon name="check" /> Confirmar y Reservar Cancha
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const precioHora = {{ (float) $cancha->precio_hora }};

    function recalcularTotal(minutos) {
        const horas = parseInt(minutos) / 60;
        const total = horas * precioHora;
        document.getElementById('label-duracion').innerText = horas + ' hora(s)';
        document.getElementById('label-total').innerText = 'S/. ' + total.toFixed(2);
    }
</script>
</x-layouts.app>
