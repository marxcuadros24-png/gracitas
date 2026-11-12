<x-layouts.app :title="$cancha->nombre . ' - Disponibilidad y Reserva'">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('home') }}">Inicio</a> &raquo;
        <a href="{{ route('canchas.establecimiento', $cancha->establecimiento_id) }}">{{ $cancha->establecimiento->nombre }}</a> &raquo;
        <span>{{ $cancha->nombre }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; align-items: start;">
        <!-- Columna Principal: Selector de Fecha y Cuadrícula de Disponibilidad -->
        <div>
            <div class="card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <span style="font-size: 0.8rem; background: var(--primary-light); color: var(--primary); padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">
                            {{ $cancha->establecimiento->nombre }}
                        </span>
                        <h1 style="font-size: 1.8rem; margin-top: 6px;">{{ $cancha->nombre }}</h1>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 1.6rem; font-weight: 800; color: var(--primary);">
                            S/. {{ number_format($cancha->precio_hora, 2) }}
                        </span>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">/ hora</span>
                    </div>
                </div>

                <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
                    {{ $cancha->descripcion ?: 'Cancha con césped sintético de alta calidad, iluminación LED de excelente visibilidad nocturna y tribunas.' }}
                </p>

                <!-- Formulario Selector de Fecha -->
                <div style="background: #f8fafc; padding: 18px; border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <label for="fecha_selector" class="form-label" style="margin-bottom: 4px; font-weight: 700;">
                            <x-icon name="calendar" /> Selecciona la fecha para consultar disponibilidad:
                        </label>
                        <input type="date" id="fecha_selector" class="form-control" value="{{ $fechaSeleccionada }}" min="{{ date('Y-m-d') }}" onchange="cambiarFecha(this.value)" style="max-width: 240px; font-weight: 600;">
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-outline btn-sm" onclick="cambiarFecha('{{ date('Y-m-d') }}')">Hoy</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="cambiarFecha('{{ date('Y-m-d', strtotime('+1 day')) }}')">Mañana</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="cambiarFecha('{{ date('Y-m-d', strtotime('+2 days')) }}')">Pasado Mañana</button>
                    </div>
                </div>
            </div>

            <!-- Leyenda de Estados -->
            <div style="display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; font-size: 0.85rem;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #10b981; display: inline-block;"></span>
                    <span>Disponible (Click para reservar)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #ef4444; display: inline-block;"></span>
                    <span>Reservado / Ocupado</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #f59e0b; display: inline-block;"></span>
                    <span>Mantenimiento</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #cbd5e1; display: inline-block;"></span>
                    <span>Horario Pasado</span>
                </div>
            </div>

            <!-- Grilla de Horarios / Franjas -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
                    <h3 style="font-size: 1.25rem; margin: 0;">
                        Horarios de Atención para el {{ \Carbon\Carbon::parse($fechaSeleccionada)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                    </h3>
                    <span id="ws-live-status" class="badge" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; font-size: 0.78rem;">
                        ● Disponibilidad en vivo
                    </span>
                </div>

                <div id="ws-slot-banner" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 0.85rem;"></div>

                @if(!$disponibilidad['abierto'])
                    <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 24px; border-radius: 8px; text-align: center; color: #92400e;">
                        <h4><x-icon name="warning" /> Cerrado en esta fecha</h4>
                        <p style="margin-top: 6px; font-size: 0.9rem;">{{ $disponibilidad['mensaje'] }}</p>
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px;" id="slots-container">
                        @foreach($disponibilidad['slots'] as $slot)
                            @if($slot['disponible'])
                                <button type="button" class="slot-btn slot-disponible" onclick="seleccionarHorario('{{ $slot['inicio_hora'] }}', '{{ $slot['fin_hora'] }}')">
                                    <div style="font-weight: 800; font-size: 1.05rem;">{{ $slot['inicio_hora'] }}</div>
                                    <div style="font-size: 0.75rem; opacity: 0.9;">a {{ $slot['fin_hora'] }}</div>
                                    <div style="font-size: 0.75rem; margin-top: 4px; font-weight: 700; background: rgba(255,255,255,0.25); border-radius: 4px; padding: 2px;">
                                        LIBRE
                                    </div>
                                </button>
                            @elseif($slot['estado'] === 'reservado')
                                <div class="slot-btn slot-reservado">
                                    <div style="font-weight: 700;">{{ $slot['inicio_hora'] }}</div>
                                    <div style="font-size: 0.72rem;">a {{ $slot['fin_hora'] }}</div>
                                    <div style="font-size: 0.7rem; margin-top: 4px;">OCUPADO</div>
                                </div>
                            @elseif($slot['estado'] === 'bloqueado')
                                <div class="slot-btn slot-bloqueado" title="{{ $slot['detalle'] }}">
                                    <div style="font-weight: 700;">{{ $slot['inicio_hora'] }}</div>
                                    <div style="font-size: 0.72rem;">a {{ $slot['fin_hora'] }}</div>
                                    <div style="font-size: 0.7rem; margin-top: 4px;">BLOQUEADO</div>
                                </div>
                            @else
                                <div class="slot-btn slot-pasado">
                                    <div style="font-weight: 600;">{{ $slot['inicio_hora'] }}</div>
                                    <div style="font-size: 0.72rem;">a {{ $slot['fin_hora'] }}</div>
                                    <div style="font-size: 0.7rem; margin-top: 4px;">PASADO</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Columna Lateral: Resumen y Acción de Reserva -->
        <div>
            <div class="card" style="position: sticky; top: 90px; border-top: 4px solid var(--primary);">
                <h3 style="font-size: 1.2rem; margin-bottom: 14px;">Reserva tu Cancha</h3>

                <div style="background: #f8fafc; padding: 14px; border-radius: var(--radius-sm); margin-bottom: 18px; font-size: 0.875rem;">
                    <div style="margin-bottom: 8px;">
                        <strong>Complejo:</strong> {{ $cancha->establecimiento->nombre }}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Cancha:</strong> {{ $cancha->nombre }}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Fecha:</strong> <span id="resumen-fecha">{{ $fechaSeleccionada }}</span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Hora elegida:</strong> <span id="resumen-hora" style="font-weight: 700; color: var(--primary);">Ninguna (Haz click en un horario verde)</span>
                    </div>
                    <div>
                        <strong>Precio hora:</strong> S/. {{ number_format($cancha->precio_hora, 2) }}
                    </div>
                </div>

                <div id="btn-reservar-wrap" style="display: none;">
                    <form action="{{ route('cliente.reservar.paso2', $cancha->id) }}" method="GET">
                        <input type="hidden" name="fecha" id="input-fecha" value="{{ $fechaSeleccionada }}">
                        <input type="hidden" name="hora" id="input-hora" value="">
                        
                        <div class="form-group">
                            <label class="label">Duración:</label>
                            <select name="duracion" id="input-duracion" class="form-select" onchange="actualizarPrecioTotal()">
                                <option value="60">1 hora (60 minutos)</option>
                                <option value="120">2 horas (120 minutos)</option>
                                <option value="180">3 horas (180 minutos)</option>
                            </select>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-top: 10px; border-top: 1px solid var(--border);">
                            <span style="font-weight: 700;">Total a pagar:</span>
                            <span id="total-calculado" style="font-size: 1.35rem; font-weight: 800; color: var(--primary);">
                                S/. {{ number_format($cancha->precio_hora, 2) }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                            <x-icon name="ball" /> Continuar Reserva
                        </button>
                    </form>
                </div>

                <div id="aviso-click" style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 16px 0;">
                    <x-icon name="arrow-left" size="14" /> Haz click en cualquiera de los bloques <strong>verdes disponibles</strong> para proceder con tu reserva.
                </div>

                <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border); font-size: 0.8rem; color: #64748b; line-height: 1.5;">
                    <x-icon name="bulb" /> <em>Recuerda: Los pagos se realizan de forma presencial en el complejo deportivo al momento del juego (Efectivo, Yape o Plin).</em>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .slot-btn {
        padding: 12px 6px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid transparent;
        cursor: default;
        transition: all 0.2s ease;
    }
    .slot-disponible {
        background: #10b981;
        color: white;
        cursor: pointer;
        border-color: #059669;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    .slot-disponible:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.35);
    }
    .slot-disponible.selected {
        background: #064e3b;
        outline: 3px solid #34d399;
    }
    .slot-reservado {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }
    .slot-bloqueado {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }
    .slot-pasado {
        background: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
    }
    @media (max-width: 900px) {
        .container > div {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const precioBase = {{ (float) $cancha->precio_hora }};
    const canchaId = {{ (int) $cancha->id }};
    const fechaActual = @json($fechaSeleccionada);
    const disponibilidadUrl = @json(route('canchas.disponibilidad', $cancha->id));
    let horaSeleccionadaActual = null;

    function cambiarFecha(fecha) {
        const url = new URL(window.location.href);
        url.searchParams.set('fecha', fecha);
        window.location.href = url.toString();
    }

    function seleccionarHorario(horaInicio, horaFin, botonEl = null) {
        document.querySelectorAll('.slot-disponible').forEach(el => el.classList.remove('selected'));
        const target = botonEl || (typeof event !== 'undefined' ? event.currentTarget : null);
        if (target) {
            target.classList.add('selected');
        }

        horaSeleccionadaActual = horaInicio;

        document.getElementById('resumen-hora').innerText = horaInicio + ' a ' + horaFin;
        document.getElementById('input-hora').value = horaInicio;

        document.getElementById('btn-reservar-wrap').style.display = 'block';
        document.getElementById('aviso-click').style.display = 'none';

        actualizarPrecioTotal();
    }

    function actualizarPrecioTotal() {
        const duracion = parseInt(document.getElementById('input-duracion').value) || 60;
        const total = (duracion / 60) * precioBase;
        document.getElementById('total-calculado').innerText = 'S/. ' + total.toFixed(2);
    }

    function limpiarSeleccionSiNoDisponible() {
        horaSeleccionadaActual = null;
        document.getElementById('resumen-hora').innerText = 'Ninguna (Haz click en un horario verde)';
        document.getElementById('input-hora').value = '';
        document.getElementById('btn-reservar-wrap').style.display = 'none';
        document.getElementById('aviso-click').style.display = 'block';
    }

    function renderizarSlotsEnVivo(slots) {
        const container = document.getElementById('slots-container');
        if (!container || !Array.isArray(slots)) {
            return;
        }

        container.replaceChildren();
        let seleccionSigueDisponible = false;

        slots.forEach(function (slot) {
            if (slot.disponible) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-btn slot-disponible';
                if (horaSeleccionadaActual === slot.inicio_hora) {
                    btn.classList.add('selected');
                    seleccionSigueDisponible = true;
                }
                btn.addEventListener('click', function () {
                    seleccionarHorario(slot.inicio_hora, slot.fin_hora, btn);
                });

                const horaDiv = document.createElement('div');
                horaDiv.style.cssText = 'font-weight: 800; font-size: 1.05rem;';
                horaDiv.textContent = slot.inicio_hora;

                const finDiv = document.createElement('div');
                finDiv.style.cssText = 'font-size: 0.75rem; opacity: 0.9;';
                finDiv.textContent = 'a ' + slot.fin_hora;

                const badgeDiv = document.createElement('div');
                badgeDiv.style.cssText = 'font-size: 0.75rem; margin-top: 4px; font-weight: 700; background: rgba(255,255,255,0.25); border-radius: 4px; padding: 2px;';
                badgeDiv.textContent = 'LIBRE';

                btn.appendChild(horaDiv);
                btn.appendChild(finDiv);
                btn.appendChild(badgeDiv);
                container.appendChild(btn);
            } else {
                const div = document.createElement('div');
                if (slot.estado === 'reservado') {
                    div.className = 'slot-btn slot-reservado';
                } else if (slot.estado === 'bloqueado') {
                    div.className = 'slot-btn slot-bloqueado';
                    div.title = slot.detalle || '';
                } else {
                    div.className = 'slot-btn slot-pasado';
                }

                const horaDiv = document.createElement('div');
                horaDiv.style.fontWeight = slot.estado === 'pasado' ? '600' : '700';
                horaDiv.textContent = slot.inicio_hora;

                const finDiv = document.createElement('div');
                finDiv.style.fontSize = '0.72rem';
                finDiv.textContent = 'a ' + slot.fin_hora;

                const labelDiv = document.createElement('div');
                labelDiv.style.cssText = 'font-size: 0.7rem; margin-top: 4px;';
                labelDiv.textContent = slot.estado === 'reservado'
                    ? 'OCUPADO'
                    : (slot.estado === 'bloqueado' ? 'BLOQUEADO' : 'PASADO');

                div.appendChild(horaDiv);
                div.appendChild(finDiv);
                div.appendChild(labelDiv);
                container.appendChild(div);
            }
        });

        if (horaSeleccionadaActual && !seleccionSigueDisponible) {
            limpiarSeleccionSiNoDisponible();
            return false;
        }

        return true;
    }

    function mostrarBannerActualizacion(texto, alerta = false) {
        const banner = document.getElementById('ws-slot-banner');
        if (!banner) {
            return;
        }
        banner.style.display = 'block';
        banner.style.background = alerta ? '#fef2f2' : '#eff6ff';
        banner.style.borderColor = alerta ? '#fecaca' : '#bfdbfe';
        banner.style.color = alerta ? '#991b1b' : '#1e40af';
        banner.textContent = texto;
    }

    if (typeof window.Echo !== 'undefined') {
        window.Echo.channel('cancha.' + canchaId)
            .listen('.disponibilidad.actualizada', function (data) {
                if (data && data.fecha && data.fecha !== fechaActual) {
                    return;
                }

                fetch(disponibilidadUrl + '?fecha=' + encodeURIComponent(fechaActual), {
                    headers: { 'Accept': 'application/json' }
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status);
                        }
                        return response.json();
                    })
                    .then(function (payload) {
                        if (payload && payload.abierto && Array.isArray(payload.slots)) {
                            const sigueDisponible = renderizarSlotsEnVivo(payload.slots);
                            if (!sigueDisponible) {
                                mostrarBannerActualizacion('El horario que tenías seleccionado acaba de ocuparse o bloquearse. Elige otro disponible.', true);
                            } else {
                                mostrarBannerActualizacion(data.mensaje || 'Disponibilidad actualizada en tiempo real.');
                            }
                        }
                    })
                    .catch(function (err) {
                        console.warn('No se pudo refrescar la disponibilidad en vivo:', err);
                    });
            });
    }
</script>
@endpush
</x-layouts.app>
