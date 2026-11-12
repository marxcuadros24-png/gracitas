@props(['title' => null])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' | ' : '' }}GraCitas - Reserva de Canchas de Gras Sintético | Coracora, Ayacucho</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Estilos base y diseño premium de GraCitas -->
    <link rel="stylesheet" href="{{ asset('css/gracitas.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Barra de referencia rápida de cuentas para evaluación -->
    <div class="demo-bar">
        <div>
            <span><x-icon name="ball" /> <strong>GraCitas Coracora</strong> — Sistema Integral de Alquiler de Canchas Sintéticas</span>
        </div>
        <div class="demo-badges">
            <span>Cuentas de prueba:</span>
            <span class="demo-badge">Admin: admin@gracitas.com / admin123</span>
            <span class="demo-badge">Propietario: propietario@gracitas.com / propietario123</span>
            <span class="demo-badge">Cliente: cliente@gracitas.com / cliente123</span>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="navbar">
        <a href="{{ auth()->check() && !auth()->user()->hasVerifiedEmail() ? route('verification.notice') : route('home') }}" class="nav-brand">
            <img src="{{ asset('images/logo.svg') }}" alt="GraCitas Logo" class="nav-logo">
            <span>Gra<span style="color: var(--primary);">Citas</span></span>
        </a>

        <button class="mobile-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')">
            <x-icon name="menu" />
        </button>

        <ul class="nav-links">
            @auth
                @if(!auth()->user()->hasVerifiedEmail())
                    <li>
                        <a href="{{ route('verification.notice') }}" class="nav-link active" style="color: #d97706; background: #fffbeb;">
                            <x-icon name="mail" size="16" /> Verificación Pendiente
                        </a>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                        <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                            {{ ucfirst(auth()->user()->rol) }} (No Verificado)
                        </span>
                        <span style="color: #0f172a; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <x-icon name="user" /> {{ auth()->user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" title="Cerrar Sesión">
                                Salir
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Explorar Canchas</a></li>

                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a></li>
                        <li><a href="{{ route('admin.usuarios.index') }}" class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">Usuarios</a></li>
                        <li><a href="{{ route('admin.supervision.establecimientos') }}" class="nav-link {{ request()->routeIs('admin.supervision.*') ? 'active' : '' }}">Supervisión</a></li>
                        <li><a href="{{ route('admin.reportes.index') }}" class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">Reportes Globales</a></li>
                    @elseif(auth()->user()->isPropietario())
                        <li><a href="{{ route('propietario.dashboard') }}" class="nav-link {{ request()->routeIs('propietario.dashboard') ? 'active' : '' }}">Mi Dashboard</a></li>
                        <li><a href="{{ route('propietario.establecimientos.index') }}" class="nav-link {{ request()->routeIs('propietario.establecimientos.*') ? 'active' : '' }}">Mi Local</a></li>
                        <li><a href="{{ route('propietario.canchas.index') }}" class="nav-link {{ request()->routeIs('propietario.canchas.*') || request()->routeIs('propietario.horarios.*') ? 'active' : '' }}">Canchas</a></li>
                        <li><a href="{{ route('propietario.reservas.index') }}" class="nav-link {{ request()->routeIs('propietario.reservas.*') ? 'active' : '' }}">Reservas</a></li>
                        <li><a href="{{ route('propietario.pagos.index') }}" class="nav-link {{ request()->routeIs('propietario.pagos.*') ? 'active' : '' }}">Pagos Presenciales</a></li>
                        <li><a href="{{ route('propietario.reportes.index') }}" class="nav-link {{ request()->routeIs('propietario.reportes.*') ? 'active' : '' }}">Reportes</a></li>
                    @else
                        <li><a href="{{ route('cliente.reservas') }}" class="nav-link {{ request()->routeIs('cliente.reservas') ? 'active' : '' }}">Mis Reservas</a></li>
                    @endif

                    <li style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                        <span class="badge badge-role-{{ auth()->user()->rol }}">
                            {{ ucfirst(auth()->user()->rol) }}
                        </span>
                        <a href="{{ route('profile') }}" class="nav-link" style="color: #0f172a; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <x-icon name="user" /> {{ auth()->user()->name }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" title="Cerrar Sesión">
                                Salir
                            </button>
                        </form>
                    </li>
                @endif
            @else
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Explorar Canchas</a></li>
                <li><a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Iniciar Sesión</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registrarse</a></li>
            @endauth
        </ul>
    </nav>

    @auth
        @if(!auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.*'))
            <div style="background: #fffbeb; border-bottom: 1px solid #fde68a; padding: 10px 24px; font-size: 0.875rem; color: #92400e; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="warning" size="16" style="color: #d97706;" />
                    <span>Tu dirección de correo (<strong>{{ auth()->user()->email }}</strong>) aún no ha sido verificada.</span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('verification.notice') }}" class="btn btn-outline btn-sm" style="padding: 4px 12px; font-size: 0.8rem; background: white; border-color: #d97706; color: #92400e;">
                        <x-icon name="mail" size="13" /> Verificar ahora
                    </a>
                </div>
            </div>
        @endif
    @endauth

    <!-- Alertas Flash -->
    <div class="alert-wrap">
        @if(session('success'))
            <div class="alert alert-success">
                <span><x-icon name="check" /> {{ session('success') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span><x-icon name="alert" /> {{ session('error') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                <span><x-icon name="alert" /> {{ session('warning') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <span><x-icon name="lightbulb" /> {{ session('info') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <main style="flex: 1;">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="height: 36px; background: white; padding: 4px; border-radius: 6px;">
                    <h3 style="color: white; font-size: 1.4rem;">GraCitas</h3>
                </div>
                <p style="font-size: 0.9rem; line-height: 1.6;">
                    Sistema web especializado para la consulta, reserva y gestión de canchas de fútbol de gras sintético en <strong>Coracora, Ayacucho</strong>.
                </p>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 14px;">Accesos Rápidos</h4>
                <ul style="list-style: none; font-size: 0.9rem; line-height: 2;">
                    <li><a href="{{ route('home') }}" style="color: #cbd5e1;">Explorar Establecimientos</a></li>
                    <li><a href="{{ route('login') }}" style="color: #cbd5e1;">Portal de Propietarios</a></li>
                    <li><a href="{{ route('login') }}" style="color: #cbd5e1;">Iniciar Sesión</a></li>
                    <li><a href="{{ route('register') }}" style="color: #cbd5e1;">Crear Cuenta de Cliente</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 14px;">Ubicación & Contacto</h4>
                <p style="font-size: 0.9rem; line-height: 1.8; color: #cbd5e1;">
                    <x-icon name="map-pin" size="14" /> Coracora, Provincia de Parinacochas, Ayacucho, Perú.<br>
                    <x-icon name="ball" size="14" /> Canchas reglamentarias de gras sintético (F5, F7, F8).<br>
                    <x-icon name="dollar" size="14" /> Pagos presenciales al momento de jugar (Efectivo / Yape / Plin).
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} GraCitas — Todos los derechos reservados. Desarrollado con PHP, Laravel y MySQL.</p>
        </div>
    </footer>

    <div id="reverb-toast-container" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; max-width: 360px;"></div>

    @if(config('broadcasting.default') === 'reverb' && config('broadcasting.connections.reverb.key'))
        <script src="https://js.pusher.com/8.3/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
        <script>
            (function () {
                if (typeof window.Pusher === 'undefined' || typeof window.Echo === 'undefined') {
                    return;
                }

                const configuredHost = @json(config('broadcasting.connections.reverb.options.host', 'localhost'));
                const isLocalHost = ['localhost', '127.0.0.1'].includes(window.location.hostname);
                const wsHost = isLocalHost ? window.location.hostname : (configuredHost === '0.0.0.0' ? window.location.hostname : configuredHost);
                const wsPort = @json((int) config('broadcasting.connections.reverb.options.port', 8080));
                const forceTLS = @json(config('broadcasting.connections.reverb.options.scheme', 'http') === 'https');

                window.Echo = new window.Echo({
                    broadcaster: 'reverb',
                    key: @json(config('broadcasting.connections.reverb.key')),
                    wsHost: wsHost,
                    wsPort: wsPort,
                    wssPort: wsPort,
                    forceTLS: forceTLS,
                    enabledTransports: ['ws', 'wss'],
                });

                window.gracitasToast = function (titulo, detalle, tipo = 'info') {
                    const container = document.getElementById('reverb-toast-container');
                    if (!container) {
                        return;
                    }

                    const toast = document.createElement('div');
                    const accent = tipo === 'warning' ? '#f59e0b' : '#10b981';
                    toast.style.cssText = `background: #0f172a; color: #f8fafc; padding: 14px 16px; border-radius: 10px; border-left: 4px solid ${accent}; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25); font-size: 0.85rem; line-height: 1.4;`;

                    const titleEl = document.createElement('div');
                    titleEl.style.cssText = 'font-weight: 700; margin-bottom: 4px; color: #ffffff;';
                    titleEl.textContent = titulo;

                    const bodyEl = document.createElement('div');
                    bodyEl.style.cssText = 'color: #cbd5e1;';
                    bodyEl.textContent = detalle;

                    toast.appendChild(titleEl);
                    toast.appendChild(bodyEl);
                    container.appendChild(toast);

                    setTimeout(() => {
                        toast.remove();
                    }, 7000);
                };

                @auth
                    @if(auth()->user()->hasVerifiedEmail() && auth()->user()->isPropietario() && auth()->user()->establecimiento)
                        const establecimientoId = @json((int) auth()->user()->establecimiento->id);
                        window.Echo.private('establecimiento.' + establecimientoId)
                            .listen('.reserva.notificada', function (data) {
                                window.gracitasToast(
                                    'Actualización de Reserva (' + (data.codigo || '') + ')',
                                    (data.mensaje || 'Movimiento registrado') + ' — ' + (data.clienteNombre || '') + ' (' + (data.inicio || '') + ')',
                                    data.accion === 'cancelada' ? 'warning' : 'success'
                                );
                            });
                    @endif
                @endauth
            })();
        </script>
    @endif

    @stack('scripts')
</body>
</html>

