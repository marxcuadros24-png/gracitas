<x-layouts.app title="Mi Perfil">
<div class="container" style="max-width: 600px; padding-top: 30px; padding-bottom: 60px;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Mi Perfil</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Actualiza tus datos personales y credenciales de acceso</p>
            </div>
            <span class="badge badge-role-{{ $user->rol }}">
                Rol: {{ ucfirst($user->rol) }}
            </span>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono / Celular</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
                @error('telefono')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border);">

            <h4 style="margin-bottom: 14px;">Cambiar Contraseña (Opcional)</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 16px;">Déjalo en blanco si no deseas modificar tu clave actual.</p>

            <div class="form-group">
                <label for="current_password" class="form-label">Contraseña Actual</label>
                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Solo requerida si cambias de clave">
                @error('current_password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    @if(!$user->isAdmin())
        <div class="card" style="margin-top: 24px; border: 1px solid #fecaca; background: #fff5f5;">
            <h3 style="color: #991b1b; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                <x-icon name="alert" /> Zona de Peligro: Eliminar mi Cuenta
            </h3>
            <p style="color: #7f1d1d; font-size: 0.9rem; margin-bottom: 16px;">
                @if($user->isPropietario())
                    Si ya no deseas participar en GraCitas como propietario, esta acción eliminará tu cuenta, tu local deportivo, tus canchas e historial permanentemente.
                @else
                    Si ya no deseas utilizar GraCitas, esta acción eliminará tu cuenta de usuario e historial permanentemente.
                @endif
                Esta acción no se puede deshacer y requiere que no tengas reservas pendientes o en curso.
            </p>

            <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('¿Estás absolutamente seguro de que deseas eliminar permanentemente tu cuenta? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="password_delete" class="form-label" style="color: #7f1d1d; font-weight: 600;">
                        Ingresa tu contraseña para confirmar:
                    </label>
                    <input type="password" id="password_delete" name="password_delete" class="form-control" required placeholder="Tu contraseña actual" style="border-color: #fca5a5;">
                    @error('password_delete')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-danger" style="background: #dc2626; border-color: #dc2626; color: white; display: inline-flex; align-items: center; gap: 6px;">
                        <x-icon name="trash" /> Eliminar Definitivamente mi Cuenta
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
</x-layouts.app>
