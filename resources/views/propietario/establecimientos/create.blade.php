<x-layouts.app title="Registrar Establecimiento">
<div class="container" style="max-width: 650px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.establecimientos.index') }}">&larr; Volver a Establecimientos</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Registrar Mi Local Deportivo</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">Ingresa la información de tu sede o complejo en Coracora</p>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; color: #1e40af; margin-bottom: 20px;">
            <x-icon name="info" /> <strong>Información:</strong> Cada cuenta de propietario gestiona un único local deportivo. Una vez registrado, podrás crear y administrar todas tus canchas sintéticas en él.
        </div>

        <form action="{{ route('propietario.establecimientos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Complejo Deportivo</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej. Complejo Deportivo El Golazo">
                @error('nombre')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción del local y comodidades</label>
                <textarea id="descripcion" name="descripcion" rows="3" class="form-control" placeholder="Describe los servicios: iluminación, duchas, estacionamiento, venta de bebidas...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="direccion" class="form-label">Dirección en Coracora</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion') }}" required placeholder="Ej. Jr. Comercio 340, Coracora">
                @error('direccion')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="referencia" class="form-label">Referencia de ubicación</label>
                <input type="text" id="referencia" name="referencia" class="form-control" value="{{ old('referencia') }}" placeholder="Ej. A media cuadra de la Plaza de Armas">
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono / Celular de contacto</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej. 956789012">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <a href="{{ route('propietario.establecimientos.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Establecimiento</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
