<x-layouts.app title="Mi Local Deportivo">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Mi Local Deportivo</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Administras un único local deportivo en Coracora, donde puedes registrar y gestionar todas tus canchas sintéticas.
            </p>
        </div>
        @if(!$establecimiento)
            <a href="{{ route('propietario.establecimientos.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="plus" /> Registrar Mi Local
            </a>
        @else
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('propietario.canchas.index') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="ball" /> Ver Mis Canchas ({{ $establecimiento->canchas_count }})
                </a>
                <a href="{{ route('propietario.establecimientos.edit', $establecimiento->id) }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="pencil" /> Editar Local
                </a>
            </div>
        @endif
    </div>

    @if(!$establecimiento)
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <div style="margin-bottom: 16px; color: var(--primary);">
                <x-icon name="building" size="56" />
            </div>
            <h3>Aún no has registrado tu local deportivo</h3>
            <p style="color: var(--text-muted); margin-top: 6px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Como propietario debes registrar tu sede o complejo deportivo para poder comenzar a añadir tus canchas sintéticas y recibir reservas.
            </p>
            <a href="{{ route('propietario.establecimientos.create') }}" class="btn btn-primary" style="margin-top: 16px; display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="plus" /> Registrar Mi Local
            </a>
        </div>
    @else
        <div class="card" style="margin-bottom: 24px; border-left: 4px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <h3 style="margin: 0; font-size: 1.4rem; display: flex; align-items: center; gap: 8px;">
                            <x-icon name="building" /> {{ $establecimiento->nombre }}
                        </h3>
                        @if($establecimiento->activo)
                            <span class="badge" style="background: #ecfdf5; color: #065f46;">Activo</span>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #991b1b;">Inactivo</span>
                        @endif
                    </div>
                    @if($establecimiento->descripcion)
                        <p style="color: var(--text-muted); margin-bottom: 16px; max-width: 750px;">
                            {{ $establecimiento->descripcion }}
                        </p>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px;">
                        <x-icon name="map-pin" /> Dirección en Coracora
                    </span>
                    <strong>{{ $establecimiento->direccion }}</strong>
                    @if($establecimiento->referencia)
                        <div style="font-size: 0.85rem; color: var(--text-muted);">Ref: {{ $establecimiento->referencia }}</div>
                    @endif
                </div>

                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px;">
                        <x-icon name="phone" /> Teléfono de Contacto
                    </span>
                    <strong>{{ $establecimiento->telefono ?: 'No especificado' }}</strong>
                </div>

                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px;">
                        <x-icon name="ball" /> Canchas Disponibles
                    </span>
                    <strong style="color: var(--primary); font-size: 1.1rem;">
                        {{ $establecimiento->canchas_count }} cancha(s) en este local
                    </strong>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 12px 16px; background: #f8fafc; border-radius: 6px; font-size: 0.85rem; color: #475569; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="lightbulb" style="color: #ca8a04;" />
                    <span><strong>Regla del sistema:</strong> Cada propietario cuenta con un único local, y puede registrar múltiples canchas dentro de él.</span>
                </span>
                <a href="{{ route('propietario.canchas.create') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="plus" /> Añadir Nueva Cancha a este Local
                </a>
            </div>
        </div>
    @endif
</div>
</x-layouts.app>
