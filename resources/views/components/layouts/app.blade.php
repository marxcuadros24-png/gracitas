@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' | ' : '' }}GraCitas - {{ __('messages.tagline') }}</title>
    
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
            <span><x-icon name="ball" /> <strong>GraCitas Coracora</strong> — {{ __('messages.demo_banner') }}</span>
        </div>
        <div class="demo-badges">
            <span>{{ __('messages.demo_test_accounts') }}</span>
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
                            <x-icon name="mail" size="16" /> {{ __('messages.pending_verification') }}
                        </a>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; margin-left: 10px;">
                        <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                            {{ ucfirst(auth()->user()->rol) }} ({{ __('messages.unverified_badge') }})
                        </span>
                        <span style="color: #0f172a; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <x-icon name="user" /> {{ auth()->user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" title="{{ __('messages.nav.logout') }}">
                                {{ __('messages.nav.logout') }}
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav.explore') }}</a></li>

                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('messages.nav.admin_dashboard') }}</a></li>
                        <li><a href="{{ route('admin.usuarios.index') }}" class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">{{ __('messages.nav.admin_users') }}</a></li>
                        <li><a href="{{ route('admin.supervision.establecimientos') }}" class="nav-link {{ request()->routeIs('admin.supervision.*') ? 'active' : '' }}">{{ __('messages.nav.admin_supervision') }}</a></li>
                        <li><a href="{{ route('admin.reportes.index') }}" class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">{{ __('messages.nav.admin_reports') }}</a></li>
                    @elseif(auth()->user()->isPropietario())
                        <li><a href="{{ route('propietario.dashboard') }}" class="nav-link {{ request()->routeIs('propietario.dashboard') ? 'active' : '' }}">{{ __('messages.nav.owner_dashboard') }}</a></li>
                        <li><a href="{{ route('propietario.establecimientos.index') }}" class="nav-link {{ request()->routeIs('propietario.establecimientos.*') ? 'active' : '' }}">{{ __('messages.nav.owner_facility') }}</a></li>
                        <li><a href="{{ route('propietario.canchas.index') }}" class="nav-link {{ request()->routeIs('propietario.canchas.*') || request()->routeIs('propietario.horarios.*') ? 'active' : '' }}">{{ __('messages.nav.owner_pitches') }}</a></li>
                        <li><a href="{{ route('propietario.reservas.index') }}" class="nav-link {{ request()->routeIs('propietario.reservas.*') ? 'active' : '' }}">{{ __('messages.nav.owner_reservations') }}</a></li>
                        <li><a href="{{ route('propietario.pagos.index') }}" class="nav-link {{ request()->routeIs('propietario.pagos.*') ? 'active' : '' }}">{{ __('messages.nav.owner_payments') }}</a></li>
                        <li><a href="{{ route('propietario.reportes.index') }}" class="nav-link {{ request()->routeIs('propietario.reportes.*') ? 'active' : '' }}">{{ __('messages.nav.owner_reports') }}</a></li>
                    @else
                        <li><a href="{{ route('cliente.reservas') }}" class="nav-link {{ request()->routeIs('cliente.reservas') ? 'active' : '' }}">{{ __('messages.nav.my_reservations') }}</a></li>
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
                            <button type="submit" class="btn btn-outline btn-sm" title="{{ __('messages.nav.logout') }}">
                                {{ __('messages.nav.logout') }}
                            </button>
                        </form>
                    </li>
                @endif
            @else
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav.explore') }}</a></li>
                <li><a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">{{ __('messages.nav.login') }}</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('messages.nav.register') }}</a></li>
            @endauth

            <!-- Selector de Idioma (ES / EN / FR) -->
            <li class="nav-lang-item" style="display: flex; align-items: center; margin-left: 10px;">
                <div class="lang-switch-group" role="group" aria-label="{{ __('messages.nav.language') }}" style="display: inline-flex; align-items: center; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 9999px; padding: 2px 3px; gap: 2px;">
                    <a href="{{ route('locale.switch', 'es') }}" class="lang-pill {{ app()->getLocale() === 'es' ? 'active' : '' }}" title="Español" style="padding: 4px 8px; border-radius: 9999px; text-decoration: none; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; transition: all 0.2s ease; {{ app()->getLocale() === 'es' ? 'background: #047857; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.15);' : 'color: #475569;' }}">
                        <span>🇪🇸</span><span>ES</span>
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}" class="lang-pill {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English" style="padding: 4px 8px; border-radius: 9999px; text-decoration: none; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; transition: all 0.2s ease; {{ app()->getLocale() === 'en' ? 'background: #047857; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.15);' : 'color: #475569;' }}">
                        <span>🇬🇧</span><span>EN</span>
                    </a>
                    <a href="{{ route('locale.switch', 'fr') }}" class="lang-pill {{ app()->getLocale() === 'fr' ? 'active' : '' }}" title="Français" style="padding: 4px 8px; border-radius: 9999px; text-decoration: none; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 3px; transition: all 0.2s ease; {{ app()->getLocale() === 'fr' ? 'background: #047857; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.15);' : 'color: #475569;' }}">
                        <span>🇫🇷</span><span>FR</span>
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    @auth
        @if(!auth()->user()->hasVerifiedEmail() && !request()->routeIs('verification.*'))
            <div style="background: #fffbeb; border-bottom: 1px solid #fde68a; padding: 10px 24px; font-size: 0.875rem; color: #92400e; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="warning" size="16" style="color: #d97706;" />
                    <span>{{ __('messages.verification_alert', ['email' => auth()->user()->email]) }}</span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ route('verification.notice') }}" class="btn btn-outline btn-sm" style="padding: 4px 12px; font-size: 0.8rem; background: white; border-color: #d97706; color: #92400e;">
                        <x-icon name="mail" size="13" /> {{ __('messages.verify_now') }}
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
        <div class="footer-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px;">
            <!-- Columna 1: Marca y Enlaces Corporativos -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="height: 38px; background: white; padding: 4px; border-radius: 8px;">
                    <h3 style="color: white; font-size: 1.5rem; font-weight: 800;">GraCitas</h3>
                </div>
                <p style="font-size: 0.9rem; line-height: 1.6; color: #94a3b8; margin-bottom: 16px;">
                    {{ __('messages.footer.description') }}
                </p>
                <ul style="list-style: none; font-size: 0.9rem; line-height: 2.1;">
                    <li><a href="{{ route('alianzas') }}" style="color: #cbd5e1; font-weight: 600;">Alianzas</a></li>
                    <li><a href="{{ route('terminos') }}" style="color: #cbd5e1; font-weight: 600;">Estados financieros</a></li>
                    <li><a href="{{ route('privacidad') }}" style="color: #cbd5e1; font-weight: 600;">Reportes anuales</a></li>
                </ul>
            </div>

            <!-- Columna 2: Enlaces Rápidos -->
            <div>
                <h4 style="color: white; margin-bottom: 14px; font-size: 1.05rem;">{{ __('messages.footer.quick_links') }}</h4>
                <ul style="list-style: none; font-size: 0.9rem; line-height: 2.2;">
                    <li><a href="{{ route('home') }}" style="color: #cbd5e1;">{{ __('messages.footer.explore_facilities') }}</a></li>
                    <li><a href="{{ route('home') }}#como-funciona" style="color: #cbd5e1;">{{ __('messages.home.how_it_works_title') }}</a></li>
                    <li><a href="{{ route('home') }}#canchas-populares" style="color: #cbd5e1;">{{ __('messages.home.catalog_popular_title') }}</a></li>
                    <li><a href="{{ route('register') }}?rol=propietario" style="color: #6ee7b7; font-weight: 700;">{{ __('messages.home.b2b_cta') }} (B2B)</a></li>
                    <li><a href="{{ route('login') }}" style="color: #cbd5e1;">{{ __('messages.nav.login') }}</a></li>
                </ul>
            </div>

            <!-- Columna 3: Cumplimiento Legal y Pasarelas -->
            <div>
                <h4 style="color: white; margin-bottom: 14px; font-size: 1.05rem;">Cumplimiento Legal</h4>
                <ul style="list-style: none; font-size: 0.9rem; line-height: 2.2;">
                    <li><a href="{{ route('terminos') }}" style="color: #cbd5e1;">{{ __('messages.footer.terms') }}</a></li>
                    <li><a href="{{ route('privacidad') }}" style="color: #cbd5e1;">{{ __('messages.footer.privacy') }}</a></li>
                    <li>
                        <a href="{{ route('swagger.ui') }}" style="color: #a7f3d0;" target="_blank">
                            ⚡ Swagger API / OpenAPI 3.0
                        </a>
                    </li>
                </ul>
                <div style="margin-top: 16px;">
                    <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 8px;">Métodos de pago aceptados:</p>
                    <div class="payment-badges-row">
                        <span class="payment-badge yape">Yape</span>
                        <span class="payment-badge plin">Plin</span>
                        <span class="payment-badge izipay">IziPay</span>
                        <span class="payment-badge niubiz">Niubiz</span>
                        <span class="payment-badge">💳 Tarjetas</span>
                        <span class="payment-badge" style="background: #ecfdf5; color: #065f46; border-color: #6ee7b7;">💵 Efectivo</span>
                    </div>
                </div>
            </div>

            <!-- Columna 4: Libro de Reclamaciones -->
            <div>
                <a href="mailto:soporte@gracitas.com?subject=Libro%20de%20Reclamaciones%20GraCitas" style="display: inline-flex; flex-direction: column; align-items: flex-start; text-decoration: none;">
                    <span style="color: #38bdf8; font-weight: 800; font-size: 1.15rem; letter-spacing: -0.2px; margin-bottom: 8px;">
                        Libro de Reclamaciones
                    </span>
                    <svg width="86" height="48" viewBox="0 0 80 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 12C35 5 20 4 5 7V37C20 34 35 35 40 42C45 35 60 34 75 37V7C60 4 45 5 40 12Z" fill="#0284c7" stroke="#38bdf8" stroke-width="1.5"/>
                        <path d="M40 12V42" stroke="#0ea5e9" stroke-width="2"/>
                        <path d="M8 11C22 8 34 9 38 13V37C34 33 22 32 8 35V11Z" fill="#ffffff" fill-opacity="0.95"/>
                        <path d="M72 11C58 8 46 9 42 13V37C46 33 58 32 72 35V11Z" fill="#f8fafc" fill-opacity="0.95"/>
                        <line x1="12" y1="16" x2="33" y2="16" stroke="#94a3b8" stroke-width="1.2"/>
                        <line x1="12" y1="21" x2="31" y2="21" stroke="#94a3b8" stroke-width="1.2"/>
                        <line x1="12" y1="26" x2="33" y2="26" stroke="#94a3b8" stroke-width="1.2"/>
                        <line x1="47" y1="16" x2="68" y2="16" stroke="#94a3b8" stroke-width="1.2"/>
                        <line x1="49" y1="21" x2="68" y2="21" stroke="#94a3b8" stroke-width="1.2"/>
                        <line x1="47" y1="26" x2="66" y2="26" stroke="#94a3b8" stroke-width="1.2"/>
                    </svg>
                </a>
                <p style="font-size: 0.78rem; color: #94a3b8; margin-top: 10px; line-height: 1.4;">
                    Conforme a lo establecido en la Ley N° 29571 (Código de Protección y Defensa del Consumidor).
                </p>
                <p style="font-size: 0.78rem; color: #64748b; margin-top: 8px;">
                    <x-icon name="map-pin" size="13" /> Coracora, Parinacochas, Ayacucho.
                </p>
            </div>
        </div>

        <!-- Barra Institucional: Sello de Auditoría EY y Redes Sociales -->
        <div style="margin-top: 36px; padding-top: 24px; border-top: 1px solid rgba(255, 255, 255, 0.12); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <!-- Sello de Auditoría EY -->
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="position: relative; line-height: 1; text-align: left;">
                    <div style="width: 32px; height: 3.5px; background: #ffe600; transform: rotate(-8deg); margin-bottom: 2px; border-radius: 2px;"></div>
                    <span style="font-family: 'Arial Black', sans-serif; font-size: 1.65rem; font-weight: 900; color: #ffffff; letter-spacing: -1.5px;">EY</span>
                    <div style="font-size: 0.52rem; color: #94a3b8; font-weight: 600; line-height: 1.1; margin-top: 2px;">
                        Building a better<br>working world
                    </div>
                </div>
                <div style="text-align: left; padding-left: 12px; border-left: 1px solid rgba(255, 255, 255, 0.2); line-height: 1.25;">
                    <span style="font-size: 0.72rem; color: #94a3b8; display: block;">Auditados por</span>
                    <strong style="font-size: 0.98rem; color: #ffffff; font-weight: 800; letter-spacing: 0.2px;">Ernst &amp; Young</strong>
                </div>
            </div>

            <!-- Redes Sociales circulares idénticas a la imagen del usuario -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <!-- Instagram -->
                <a href="https://instagram.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>
                <!-- Facebook -->
                <a href="https://facebook.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                </a>
                <!-- LinkedIn -->
                <a href="https://linkedin.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="LinkedIn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                        <rect width="4" height="12" x="2" y="9"/>
                        <circle cx="4" cy="4" r="2"/>
                    </svg>
                </a>
                <!-- TikTok -->
                <a href="https://tiktok.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="TikTok">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/>
                    </svg>
                </a>
                <!-- YouTube -->
                <a href="https://youtube.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="YouTube">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/>
                        <polygon points="10 15 15 12 10 9 10 15"/>
                    </svg>
                </a>
                <!-- X (Twitter) -->
                <a href="https://x.com" target="_blank" rel="noopener" style="color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s;" title="X (Twitter)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-bottom" style="margin-top: 24px;">
            <p>&copy; {{ date('Y') }} GraCitas — {{ __('messages.footer.rights_reserved') }} | Coracora, Ayacucho.</p>
        </div>
    </footer>

    <!-- Mobile Quick Sticky Action Bar -->
    <div class="mobile-sticky-bar">
        <div>
            <div style="font-size: 0.72rem; color: #a7f3d0; font-weight: 700; text-transform: uppercase;">
                <span class="pulse-dot"></span> {{ __('messages.home.stats_live') }}
            </div>
            <div style="font-size: 0.88rem; color: #ffffff; font-weight: 800;">
                Canchas en Coracora
            </div>
        </div>
        <a href="#canchas-populares" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.85rem; font-weight: 700; border-radius: 9999px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);">
            <x-icon name="ball" /> {{ __('messages.home.cta_primary') }}
        </a>
    </div>

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
                    enabledTransports: forceTLS ? ['wss'] : ['ws'],
                });

                if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                    window.Echo.connector.pusher.connection.bind('state_change', function (states) {
                        if (window.console && console.debug) {
                            console.debug('[Reverb WS] Estado:', states.current);
                        }
                    });
                }

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

