<x-layouts.app title="Crear Usuario">
<div class="container" style="max-width: 600px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('admin.usuarios.index') }}">&larr; Volver a Usuarios</a>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 6px;">Crear Nuevo Usuario</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
            Registra una cuenta de Administrador, Propietario o Cliente
        </p>

        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="rol" class="form-label">Rol del Usuario</label>
                <select name="rol" id="rol" class="form-select" required>
                    <option value="cliente" {{ old('rol') === 'cliente' ? 'selected' : '' }}>Cliente (Reserva canchas)</option>
                    <option value="propietario" {{ old('rol') === 'propietario' ? 'selected' : '' }}>Propietario (Administra establecimientos)</option>
                    <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador (Control total)</option>
                </select>
                @error('rol')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Ej. Roberto Quispe">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="usuario@gracitas.com">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono / Celular</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej. 966112233">
                @error('telefono')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Mínimo 6 caracteres">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="activo" class="form-label">Estado de la Cuenta</label>
                <select name="activo" id="activo" class="form-select">
                    <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
