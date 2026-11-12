<x-layouts.app :title="'Editar Usuario - ' . $usuario->name">
<div class="container" style="max-width: 600px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('admin.usuarios.index') }}">&larr; Volver a Usuarios</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Editar Usuario: {{ $usuario->name }}</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
            Modifica roles, datos o restablece contraseñas
        </p>

        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="rol" class="form-label">Rol del Usuario</label>
                <select name="rol" id="rol" class="form-select" required>
                    <option value="cliente" {{ old('rol', $usuario->rol) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="propietario" {{ old('rol', $usuario->rol) === 'propietario' ? 'selected' : '' }}>Propietario</option>
                    <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('rol')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono / Celular</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}">
            </div>

            <div class="form-group">
                <label for="activo" class="form-label">Estado de la Cuenta</label>
                <select name="activo" id="activo" class="form-select">
                    <option value="1" {{ old('activo', $usuario->activo) ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !old('activo', $usuario->activo) ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para mantener la contraseña actual">
                <small style="color: var(--text-muted);">Solo escribe aquí si deseas restablecer la contraseña del usuario.</small>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; flex-wrap: wrap; gap: 12px;">
                @if($usuario->id !== auth()->id())
                    <button type="button" class="btn btn-danger" onclick="if(confirm('¿Estás seguro de eliminar permanentemente a {{ $usuario->name }}? Esta acción no se puede revertir.')) document.getElementById('delete-user-form').submit();" style="background: #dc2626; border-color: #dc2626; color: white; display: inline-flex; align-items: center; gap: 6px;">
                        <x-icon name="trash" /> Eliminar Usuario
                    </button>
                @else
                    <div></div>
                @endif
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>

        @if($usuario->id !== auth()->id())
            <form id="delete-user-form" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>
</x-layouts.app>
