<x-layouts.app title="Gestión de Usuarios">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Gestión de Usuarios (RF-01 & RF-03)</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Administración de cuentas, roles y control de acceso al sistema GraCitas
            </p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px;">
            <x-icon name="plus" /> Crear Nuevo Usuario
        </a>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card" style="margin-bottom: 20px; padding: 18px;">
        <form action="{{ route('admin.usuarios.index') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 220px;">
                <label class="form-label">Buscar por Nombre, Correo o Teléfono:</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar usuario...">
            </div>

            <div style="min-width: 160px;">
                <label class="form-label">Rol:</label>
                <select name="rol" class="form-select">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="propietario" {{ request('rol') === 'propietario' ? 'selected' : '' }}>Propietario</option>
                    <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>

            <div style="min-width: 140px;">
                <label class="form-label">Estado:</label>
                <select name="activo" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
                Filtrar
            </button>
            @if(request()->anyFilled(['q', 'rol', 'activo']))
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline" style="padding: 10px 14px;">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre y Apellidos</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        <tr>
                            <td>#{{ $u->id }}</td>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->telefono ?: 'No registrado' }}</td>
                            <td>
                                <span class="badge badge-role-{{ $u->rol }}">
                                    {{ ucfirst($u->rol) }}
                                </span>
                            </td>
                            <td>
                                @if($u->activo)
                                    <span class="badge" style="background: #ecfdf5; color: #065f46;">Activo</span>
                                @else
                                    <span class="badge" style="background: #fef2f2; color: #991b1b;">Inactivo</span>
                                @endif
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                    <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <x-icon name="pencil" /> Editar
                                    </a>

                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.toggle', $u->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $u->activo ? 'btn-outline' : 'btn-primary' }}" title="{{ $u->activo ? 'Desactivar cuenta' : 'Activar cuenta' }}">
                                                {{ $u->activo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.usuarios.destroy', $u->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $u->name }}? Se eliminarán también sus registros vinculados de forma permanente.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background: #dc2626; border-color: #dc2626; color: white; display: inline-flex; align-items: center; justify-content: center;" title="Eliminar usuario">
                                                <x-icon name="trash" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 16px;">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
