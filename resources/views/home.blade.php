<x-layouts.app title="Inicio - Canchas de Gras Sintético en Coracora">
<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #064e3b 0%, #087f68 50%, #0f172a 100%); color: white; padding: 70px 24px; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
    
    <div class="container" style="position: relative; z-index: 10; text-align: center; max-width: 900px;">
        <span style="display: inline-block; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); padding: 6px 16px; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; border: 1px solid rgba(255, 255, 255, 0.25);">
            <x-icon name="ball" /> Plataforma Oficial de Reservas en Coracora, Ayacucho
        </span>

        <h1 style="color: white; font-size: 3rem; font-weight: 900; margin-bottom: 18px; line-height: 1.15; letter-spacing: -1px;">
            Tu Cancha de Gras Sintético <br><span style="color: #6ee7b7;">Lista para el Clásico</span>
        </h1>

        <p style="font-size: 1.15rem; color: #e2e8f0; margin-bottom: 32px; max-width: 720px; margin-left: auto; margin-right: auto;">
            Consulta la disponibilidad en tiempo real de los mejores complejos deportivos de Coracora. Reserva tu horario en segundos y paga presencialmente en el establecimiento.
        </p>

        <!-- Buscador -->
        <form action="{{ route('home') }}" method="GET" style="background: white; padding: 10px; border-radius: var(--radius-lg); box-shadow: 0 15px 30px rgba(0,0,0,0.3); display: flex; gap: 10px; max-width: 680px; margin: 0 auto; flex-wrap: wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por complejo deportivo, dirección o barrio en Coracora..." style="flex: 1; min-width: 260px; padding: 12px 18px; border: none; outline: none; font-size: 1rem; border-radius: var(--radius-md);">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <x-icon name="search" /> Buscar Canchas
            </button>
        </form>

        <!-- Métricas destacadas -->
        <div style="display: flex; justify-content: center; gap: 32px; margin-top: 40px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #a7f3d0;">{{ $totalEstablecimientos }}</div>
                <div style="font-size: 0.85rem; color: #cbd5e1;">Complejos Deportivos</div>
            </div>
            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 40px;"></div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #a7f3d0;">{{ $totalCanchas }}</div>
                <div style="font-size: 0.85rem; color: #cbd5e1;">Canchas Activas</div>
            </div>
            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 40px;"></div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #a7f3d0;">100%</div>
                <div style="font-size: 0.85rem; color: #cbd5e1;">Disponibilidad en Vivo</div>
            </div>
        </div>
    </div>
</section>

<!-- Catálogo de Establecimientos y Canchas -->
<section class="container" style="padding-top: 50px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 2rem;">Establecimientos Disponibles en Coracora</h2>
            <p style="color: var(--text-muted);">Selecciona un complejo para ver sus canchas, horarios y reservar tu partido</p>
        </div>
        @if(request('q'))
            <a href="{{ route('home') }}" class="btn btn-outline btn-sm">Limpiar búsqueda</a>
        @endif
    </div>

    @if($establecimientos->isEmpty())
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <div style="margin-bottom: 12px;"><x-icon name="building" size="48" style="color: var(--text-muted);" /></div>
            <h3>No se encontraron establecimientos</h3>
            <p style="color: var(--text-muted); margin-top: 6px;">Intenta con otro término de búsqueda o limpia los filtros.</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 18px;">Ver todos</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
            @foreach($establecimientos as $est)
                <div class="card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--border);">
                    <!-- Cabecera del Complejo -->
                    <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; padding: 20px; border-radius: var(--radius-md) var(--radius-md) 0 0; margin: -24px -24px 20px -24px;">
                        <span style="font-size: 0.75rem; background: var(--primary); padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">
                            Coracora • Gras Sintético
                        </span>
                        <h3 style="color: white; margin-top: 8px; font-size: 1.3rem;">{{ $est->nombre }}</h3>
                        <p style="font-size: 0.85rem; color: #cbd5e1; margin-top: 4px;">
                            <x-icon name="map-pin" size="14" /> {{ $est->direccion }}
                            @if($est->referencia)
                                <br><small style="color: #94a3b8;">Ref: {{ $est->referencia }}</small>
                            @endif
                        </p>
                    </div>

                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 18px; line-height: 1.5; flex: 1;">
                        {{ Str::limit($est->descripcion, 120, '...') }}
                    </p>

                    <!-- Lista de canchas de este complejo -->
                    <div style="background: #f8fafc; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 18px; border: 1px solid #f1f5f9;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">
                            Canchas en este complejo:
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @forelse($est->canchas as $cancha)
                                <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <div>
                                        <strong style="font-size: 0.9rem; color: #1e293b;">{{ $cancha->nombre }}</strong>
                                        <div style="font-size: 0.78rem; color: #64748b;"><x-icon name="users" size="13" /> Capacidad: {{ $cancha->capacidad }} jugadores</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                                            S/. {{ number_format($cancha->precio_hora, 2) }}
                                        </div>
                                        <span style="font-size: 0.72rem; color: #94a3b8;">/ hora</span>
                                    </div>
                                </div>
                            @empty
                                <div style="font-size: 0.85rem; color: #94a3b8;">No tiene canchas activas registradas aún.</div>
                            @endforelse
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: auto;">
                        <a href="{{ route('canchas.establecimiento', $est->id) }}" class="btn btn-primary" style="flex: 1;">
                            <x-icon name="calendar" /> Ver Canchas y Reservar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $establecimientos->links() }}
        </div>
    @endif
</section>

<!-- Cómo funciona -->
<section style="background: #f1f5f9; padding: 60px 24px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
    <div class="container" style="text-align: center;">
        <h2 style="font-size: 2.2rem; margin-bottom: 10px;">¿Cómo funciona GraCitas?</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 40px;">
            Pensado especialmente para los futbolistas y propietarios de Coracora: rápido, transparente y con pagos presenciales.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; text-align: left;">
            <div class="card">
                <div style="margin-bottom: 12px;"><x-icon name="building" size="32" style="color: var(--primary);" /></div>
                <h4 style="margin-bottom: 8px;">1. Elige tu Cancha</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Explora los complejos de Coracora, verifica las medidas, si es techada o al aire libre y las comodidades del local.
                </p>
            </div>
            <div class="card">
                <div style="margin-bottom: 12px;"><x-icon name="clock" size="32" style="color: var(--primary);" /></div>
                <h4 style="margin-bottom: 8px;">2. Consulta Horarios Libres</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    El sistema te muestra exactamente qué horas están libres para la fecha de tu partido, sin riesgo de cruces de horario.
                </p>
            </div>
            <div class="card">
                <div style="margin-bottom: 12px;"><x-icon name="file-text" size="32" style="color: var(--primary);" /></div>
                <h4 style="margin-bottom: 8px;">3. Confirma tu Reserva</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Al confirmar, tu turno queda bloqueado inmediatamente con tu código único de reserva y tu comprobante digital.
                </p>
            </div>
            <div class="card">
                <div style="margin-bottom: 12px;"><x-icon name="dollar" size="32" style="color: var(--primary);" /></div>
                <h4 style="margin-bottom: 8px;">4. Paga en el Establecimiento</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Cancela en ventanilla al llegar a jugar mediante Efectivo, Yape o Plin. El propietario registrará tu pago al instante.
                </p>
            </div>
        </div>
    </div>
</section>
</x-layouts.app>
