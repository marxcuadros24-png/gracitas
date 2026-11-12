<x-layouts.app title="Registrar Nueva Cancha">
<div class="container" style="max-width: 650px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.canchas.index') }}">&larr; Volver a Canchas</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Nueva Cancha de Gras</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">Configura el tipo de campo sintético, capacidad y tarifa por hora</p>

        <form action="{{ route('propietario.canchas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Local Deportivo</label>
                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px;">
                    <x-icon name="building" /> {{ $establecimiento->nombre }} <span style="font-weight: 400; color: var(--text-muted); font-size: 0.85rem;">({{ $establecimiento->direccion }})</span>
                </div>
                <input type="hidden" name="establecimiento_id" value="{{ $establecimiento->id }}">
            </div>

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre o Identificador de la Cancha</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej. Cancha 1 - Fútbol 7">
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Características (Tipo de césped, iluminación, techado...)</label>
                <textarea id="descripcion" name="descripcion" rows="3" class="form-control" placeholder="Ej. Césped de 50mm con caucho, iluminación LED blanca, arcos de 5m con redes nuevas...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="capacidad" class="form-label">Capacidad de Jugadores</label>
                    <input type="number" id="capacidad" name="capacidad" class="form-control" value="{{ old('capacidad', 12) }}" required min="2" max="30">
                    <small style="color: var(--text-muted);">Ej. 10 para F5, 14 para F7</small>
                    @error('capacidad')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="precio_hora" class="form-label">Precio por Hora (S/.)</label>
                    <input type="number" step="0.50" id="precio_hora" name="precio_hora" class="form-control" value="{{ old('precio_hora', 50.00) }}" required min="0">
                    <small style="color: var(--text-muted);">Tarifa base en Soles</small>
                    @error('precio_hora')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="duracion_reserva_min" class="form-label">Duración mínima de intervalo</label>
                <select name="duracion_reserva_min" id="duracion_reserva_min" class="form-select" required>
                    <option value="60" {{ old('duracion_reserva_min') == 60 ? 'selected' : '' }}>60 minutos (1 hora estándar)</option>
                    <option value="90" {{ old('duracion_reserva_min') == 90 ? 'selected' : '' }}>90 minutos (1 hora y media)</option>
                    <option value="120" {{ old('duracion_reserva_min') == 120 ? 'selected' : '' }}>120 minutos (2 horas)</option>
                </select>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 14px; border-radius: 6px; font-size: 0.85rem; color: #166534; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <x-icon name="lightbulb" />
                <span>Al guardar, se configurará automáticamente el horario de atención de Lunes a Domingo de 07:00 a 23:00, el cual podrás personalizar en cualquier momento.</span>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('propietario.canchas.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Cancha</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
