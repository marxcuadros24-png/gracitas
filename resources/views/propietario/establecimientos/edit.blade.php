<x-layouts.app title="Editar Establecimiento">
<div class="container" style="max-width: 650px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.establecimientos.index') }}">&larr; Volver a Establecimientos</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Editar Establecimiento</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">Actualiza la información de tu complejo deportivo</p>

        <form action="{{ route('propietario.establecimientos.update', $establecimiento->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Complejo Deportivo</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $establecimiento->nombre) }}" required>
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" class="form-control">{{ old('descripcion', $establecimiento->descripcion) }}</textarea>
            </div>

            <div class="form-group">
                <label for="direccion" class="form-label">Dirección en Coracora</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $establecimiento->direccion) }}" required>
            </div>

            <div class="form-group">
                <label for="referencia" class="form-label">Referencia</label>
                <input type="text" id="referencia" name="referencia" class="form-control" value="{{ old('referencia', $establecimiento->referencia) }}">
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono de contacto</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $establecimiento->telefono) }}">
            </div>

            <div class="form-group">
                <label for="activo" class="form-label">Estado</label>
                <select name="activo" id="activo" class="form-select">
                    <option value="1" {{ old('activo', $establecimiento->activo) ? 'selected' : '' }}>Activo (Disponible para reservas)</option>
                    <option value="0" {{ !old('activo', $establecimiento->activo) ? 'selected' : '' }}>Inactivo (Ocultar temporalmente)</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <a href="{{ route('propietario.establecimientos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
