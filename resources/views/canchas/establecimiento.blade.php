<x-layouts.app :title="$establecimiento->nombre . ' - Canchas de Gras'">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('home') }}">Inicio</a> &raquo;
        <span>{{ $establecimiento->nombre }}</span>
    </div>

    <!-- Header del Establecimiento -->
    <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #064e3b 0%, #087f68 100%); color: white; border: none;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                    Complejo Deportivo • Coracora
                </span>
                <h1 style="color: white; font-size: 2.2rem; margin-top: 10px;">{{ $establecimiento->nombre }}</h1>
                <p style="color: #e2e8f0; font-size: 1rem; margin-top: 6px; max-width: 750px;">
                    {{ $establecimiento->descripcion }}
                </p>
                <div style="display: flex; gap: 20px; margin-top: 18px; font-size: 0.9rem; flex-wrap: wrap; color: #cbd5e1;">
                    <div><x-icon name="map-pin" size="14" /> <strong>Dirección:</strong> {{ $establecimiento->direccion }}</div>
                    @if($establecimiento->referencia)
                        <div><x-icon name="info" size="14" /> <strong>Referencia:</strong> {{ $establecimiento->referencia }}</div>
                    @endif
                    @if($establecimiento->telefono)
                        <div><x-icon name="phone" size="14" /> <strong>Contacto:</strong> {{ $establecimiento->telefono }}</div>
                    @endif
                </div>
            </div>
            <div>
                <span class="badge" style="background: #10b981; color: white; padding: 6px 14px; font-size: 0.9rem;">
                    Abierto para Reservas
                </span>
            </div>
        </div>
    </div>

    <!-- Canchas del Complejo -->
    <h2 style="font-size: 1.8rem; margin-bottom: 20px;">Canchas de Gras Sintético Disponibles</h2>

    @if($establecimiento->canchas->isEmpty())
        <div class="card" style="text-align: center; padding: 40px;">
            <p style="color: var(--text-muted);">Este establecimiento no tiene canchas activas en este momento.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 24px;">
            @foreach($establecimiento->canchas as $cancha)
                <div class="card" style="display: flex; flex-direction: column; border-top: 4px solid var(--primary);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <h3 style="font-size: 1.3rem;">{{ $cancha->nombre }}</h3>
                        <span style="font-weight: 800; color: var(--primary); font-size: 1.25rem;">
                            S/. {{ number_format($cancha->precio_hora, 2) }}
                            <small style="font-size: 0.75rem; font-weight: normal; color: var(--text-muted);">/ hr</small>
                        </span>
                    </div>

                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 16px; flex: 1;">
                        {{ $cancha->descripcion ?: 'Césped sintético de alta durabilidad, con iluminación LED nocturna y arcos reglamentarios.' }}
                    </p>

                    <div style="background: #f8fafc; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <div><x-icon name="users" size="13" /> <strong>Capacidad:</strong> {{ $cancha->capacidad }} jugadores</div>
                        <div><x-icon name="clock" size="13" /> <strong>Intervalo:</strong> {{ $cancha->duracion_reserva_min }} minutos</div>
                    </div>

                    <a href="{{ route('canchas.detalle', $cancha->id) }}" class="btn btn-primary" style="width: 100%;">
                        <x-icon name="calendar" /> Ver Horarios y Reservar Cancha
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
</x-layouts.app>
