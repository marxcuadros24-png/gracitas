<x-layouts.app title="Panel de Administración General">
<div class="container" style="padding-top: 30px; padding-bottom: 60px;">
    <!-- Encabezado -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Panel de Administración del Sistema</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Supervisión global de usuarios, establecimientos, canchas sintéticas y recaudación en Coracora
            </p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                <x-icon name="user" /> Crear Usuario
            </a>
            <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline">
                <x-icon name="chart" /> Reportes Generales
            </a>
        </div>
    </div>

    <!-- Tarjetas de Métricas Globales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 30px;">
        <div class="card" style="border-left: 4px solid var(--primary);">
            <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Usuarios</span>
            <div style="font-size: 2rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalUsuarios }}</div>
            <small style="color: var(--text-muted);">{{ $totalClientes }} clientes &bull; {{ $totalPropietarios }} propietarios</small>
        </div>

        <div class="card" style="border-left: 4px solid #3b82f6;">
            <span style="font-size: 0.78rem; color: #1e40af; text-transform: uppercase; font-weight: 700;">Establecimientos</span>
            <div style="font-size: 2rem; font-weight: 800; color: #1e40af; margin-top: 4px;">{{ $totalEstablecimientos }}</div>
            <small style="color: var(--text-muted);">Complejos en Coracora</small>
        </div>

        <div class="card" style="border-left: 4px solid #10b981;">
            <span style="font-size: 0.78rem; color: #065f46; text-transform: uppercase; font-weight: 700;">Canchas Sintéticas</span>
            <div style="font-size: 2rem; font-weight: 800; color: #065f46; margin-top: 4px;">{{ $totalCanchas }}</div>
            <small style="color: var(--text-muted);">Campos deportivos activos</small>
        </div>

        <div class="card" style="border-left: 4px solid #f59e0b;">
            <span style="font-size: 0.78rem; color: #92400e; text-transform: uppercase; font-weight: 700;">Total Reservas</span>
            <div style="font-size: 2rem; font-weight: 800; color: #92400e; margin-top: 4px;">{{ $totalReservas }}</div>
            <small style="color: var(--text-muted);">{{ $reservasHoy }} partidos hoy</small>
        </div>

        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <span style="font-size: 0.78rem; color: #5b21b6; text-transform: uppercase; font-weight: 700;">Recaudación Global</span>
            <div style="font-size: 1.8rem; font-weight: 800; color: #5b21b6; margin-top: 4px;">S/. {{ number_format($totalIngresosGlobal, 2) }}</div>
            <small style="color: var(--text-muted);">Pagos presenciales registrados</small>
        </div>
    </div>

    <!-- Actividad Reciente del Sistema -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Últimas Reservas -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.15rem;">Últimas Reservas en la Plataforma</h3>
                <a href="{{ route('admin.supervision.reservas') }}" class="btn btn-outline btn-sm">Ver todas</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Complejo / Cancha</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasReservas as $r)
                            <tr>
                                <td><strong style="color: var(--primary); font-family: monospace;">{{ $r->codigo }}</strong></td>
                                <td>{{ $r->cancha->establecimiento->nombre }} &bull; {{ $r->cancha->nombre }}</td>
                                <td>{{ $r->cliente->name }}</td>
                                <td>{{ $r->inicio->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $r->estado_badge['class'] }}">
                                        {{ $r->estado_badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Últimos Usuarios -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.15rem;">Últimos Usuarios</h3>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline btn-sm">Gestionar</a>
            </div>

            <div style="padding: 14px 20px;">
                @foreach($ultimosUsuarios as $u)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <div>
                            <strong>{{ $u->name }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->email }}</div>
                        </div>
                        <span class="badge badge-role-{{ $u->rol }}">
                            {{ ucfirst($u->rol) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    .container > div:nth-child(3) {
        grid-template-columns: 1fr !important;
    }
}
</style>
</x-layouts.app>
