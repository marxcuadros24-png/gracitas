<x-layouts.app title="Bloqueos de Horario y Mantenimiento">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Bloqueo de Horarios y Mantenimiento</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Inhabilita franjas de tiempo para mantenimiento, eventos vecinales o causas climáticas e impide reservas online
            </p>
        </div>
        <a href="{{ route('propietario.canchas.index') }}" class="btn btn-outline btn-sm">
            &larr; Volver a Canchas
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 360px 1fr; gap: 24px; align-items: start;">
        <!-- Formulario para Nuevo Bloqueo -->
        <div class="card" style="border-top: 4px solid #f59e0b;">
            <h3 style="font-size: 1.15rem; margin-bottom: 6px;">Nuevo Bloqueo</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 18px;">
                La franja seleccionada se marcará como no disponible para clientes
            </p>

            <form action="{{ route('propietario.bloqueos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="cancha_id" class="form-label">Cancha a bloquear</label>
                    <select name="cancha_id" id="cancha_id" class="form-select" required>
                        @foreach($canchas as $cancha)
                            <option value="{{ $cancha->id }}">
                                {{ $cancha->establecimiento->nombre }} &bull; {{ $cancha->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="hora_inicio" class="form-label">Hora Inicio</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" value="08:00" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_fin" class="form-label">Hora Fin</label>
                        <input type="time" name="hora_fin" id="hora_fin" value="11:00" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="motivo" class="form-label">Motivo</label>
                    <input type="text" name="motivo" id="motivo" class="form-control" placeholder="Ej. Peinado de césped y caucho, lluvia intensa..." required>
                </div>

                <button type="submit" class="btn btn-warning" style="width: 100%;">
                    <x-icon name="ban" /> Registrar Bloqueo
                </button>
            </form>
        </div>

        <!-- Tabla de Bloqueos Vigentes e Históricos -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 18px 24px; border-bottom: 1px solid var(--border);">
                <h3 style="font-size: 1.15rem;">Franjas Horarias Bloqueadas</h3>
            </div>

            @if($bloqueos->isEmpty())
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    No hay bloqueos de horario activos en tus canchas.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cancha</th>
                                <th>Fecha y Horario</th>
                                <th>Motivo</th>
                                <th style="text-align: right;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bloqueos as $b)
                                <tr>
                                    <td>
                                        <strong>{{ $b->cancha->nombre }}</strong>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">
                                            {{ $b->cancha->establecimiento->nombre }}
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $b->inicio->format('d/m/Y') }}</strong>
                                        <div style="font-size: 0.8rem; color: #92400e;">
                                            {{ $b->inicio->format('H:i') }} a {{ $b->fin->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>{{ $b->motivo ?: 'Sin motivo especificado' }}</td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('propietario.bloqueos.destroy', $b->id) }}" method="POST" onsubmit="return confirm('¿Deseas desbloquear este horario y dejarlo disponible nuevamente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Desbloquear
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 16px;">
                    {{ $bloqueos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media (max-width: 850px) {
    .container > div {
        grid-template-columns: 1fr !important;
    }
}
</style>
</x-layouts.app>
