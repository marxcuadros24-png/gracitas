<x-layouts.app :title="'Editar Cancha - ' . $cancha->nombre">
<div class="container" style="max-width: 650px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.canchas.index') }}">&larr; Volver a Canchas</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Editar Cancha: {{ $cancha->nombre }}</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">Actualiza los detalles y la tarifa por hora de tu cancha</p>

        <form action="{{ route('propietario.canchas.update', $cancha->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Local Deportivo</label>
                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px;">
                    <x-icon name="building" /> {{ $establecimiento->nombre }} <span style="font-weight: 400; color: var(--text-muted); font-size: 0.85rem;">({{ $establecimiento->direccion }})</span>
                </div>
                <input type="hidden" name="establecimiento_id" value="{{ $establecimiento->id }}">
            </div>

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre de la Cancha</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $cancha->nombre) }}" required>
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Características</label>
                <textarea id="descripcion" name="descripcion" rows="3" class="form-control">{{ old('descripcion', $cancha->descripcion) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="capacidad" class="form-label">Capacidad de Jugadores</label>
                    <input type="number" id="capacidad" name="capacidad" class="form-control" value="{{ old('capacidad', $cancha->capacidad) }}" required min="2" max="30">
                </div>

                <div class="form-group">
                    <label for="precio_hora" class="form-label">Precio por Hora (S/.)</label>
                    <input type="number" step="0.50" id="precio_hora" name="precio_hora" class="form-control" value="{{ old('precio_hora', $cancha->precio_hora) }}" required min="0">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="duracion_reserva_min" class="form-label">Duración mínima (min)</label>
                    <select name="duracion_reserva_min" id="duracion_reserva_min" class="form-select">
                        <option value="60" {{ old('duracion_reserva_min', $cancha->duracion_reserva_min) == 60 ? 'selected' : '' }}>60 min</option>
                        <option value="90" {{ old('duracion_reserva_min', $cancha->duracion_reserva_min) == 90 ? 'selected' : '' }}>90 min</option>
                        <option value="120" {{ old('duracion_reserva_min', $cancha->duracion_reserva_min) == 120 ? 'selected' : '' }}>120 min</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="activa" class="form-label">Estado de la Cancha</label>
                    <select name="activa" id="activa" class="form-select">
                        <option value="1" {{ old('activa', $cancha->activa) ? 'selected' : '' }}>Activa (Disponible)</option>
                        <option value="0" {{ !old('activa', $cancha->activa) ? 'selected' : '' }}>Inactiva (No disponible)</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <a href="{{ route('propietario.canchas.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
