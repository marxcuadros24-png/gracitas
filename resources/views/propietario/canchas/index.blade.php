<x-layouts.app title="Mis Canchas">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Mis Canchas de Gras Sintético</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Configura características, tarifas y horarios de atención por cancha</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('propietario.bloqueos.index') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="ban" /> Bloqueos / Mantenimiento
            </a>
            <a href="{{ route('propietario.canchas.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="plus" /> Registrar Nueva Cancha
            </a>
        </div>
    </div>

    @if(!$establecimiento)
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <div style="margin-bottom: 16px; color: var(--primary);">
                <x-icon name="building" size="56" />
            </div>
            <h3>Primero debes registrar tu local deportivo</h3>
            <p style="color: var(--text-muted); margin-top: 6px;">Para crear y gestionar canchas, registra la información de tu sede o complejo en Coracora.</p>
            <a href="{{ route('propietario.establecimientos.create') }}" class="btn btn-primary" style="margin-top: 16px; display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="plus" /> Registrar Mi Local
            </a>
        </div>
    @elseif($canchas->isEmpty())
        <div class="card" style="margin-bottom: 20px; background: #f8fafc; border-left: 4px solid var(--primary); padding: 14px 18px; display: flex; align-items: center; gap: 8px;">
            <x-icon name="building" />
            <span><strong>Local:</strong> {{ $establecimiento->nombre }} ({{ $establecimiento->direccion }})</span>
        </div>
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <div style="margin-bottom: 16px; color: var(--primary);">
                <x-icon name="ball" size="56" />
            </div>
            <h3>Aún no has registrado canchas en {{ $establecimiento->nombre }}</h3>
            <p style="color: var(--text-muted); margin-top: 6px;">Puedes registrar tantas canchas como disponga tu complejo deportivo.</p>
            <a href="{{ route('propietario.canchas.create') }}" class="btn btn-primary" style="margin-top: 16px; display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="plus" /> Registrar Primera Cancha
            </a>
        </div>
    @else
        <div class="card" style="margin-bottom: 20px; background: #f8fafc; border-left: 4px solid var(--primary); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <x-icon name="building" />
                <span><strong>Local:</strong> {{ $establecimiento->nombre }} &bull; <span style="color: var(--text-muted);">{{ $establecimiento->direccion }}</span></span>
            </div>
            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600;">
                {{ $canchas->count() }} cancha(s) registradas
            </span>
        </div>
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cancha</th>
                            <th>Complejo Deportivo</th>
                            <th>Capacidad</th>
                            <th>Precio por Hora</th>
                            <th>Duración Slot</th>
                            <th>Estado</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($canchas as $cancha)
                            <tr>
                                <td>
                                    <strong>{{ $cancha->nombre }}</strong>
                                    @if($cancha->descripcion)
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">
                                            {{ Str::limit($cancha->descripcion, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $cancha->establecimiento->nombre }}</td>
                                <td>
                                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                                        <x-icon name="users" /> {{ $cancha->capacidad }} jugadores
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: var(--primary); font-size: 1.05rem;">
                                        S/. {{ number_format($cancha->precio_hora, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                                        <x-icon name="clock" /> {{ $cancha->duracion_reserva_min }} min
                                    </span>
                                </td>
                                <td>
                                    @if($cancha->activa)
                                        <span class="badge" style="background: #ecfdf5; color: #065f46;">Activa</span>
                                    @else
                                        <span class="badge" style="background: #fef2f2; color: #991b1b;">Inactiva</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                        <a href="{{ route('propietario.horarios.edit', $cancha->id) }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 4px;" title="Configurar Horarios">
                                            <x-icon name="clock" /> Horarios
                                        </a>
                                        <a href="{{ route('propietario.canchas.edit', $cancha->id) }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 4px;">
                                            <x-icon name="pencil" /> Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
</x-layouts.app>
