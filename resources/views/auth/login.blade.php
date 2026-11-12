<x-layouts.app title="Acceso al Sistema - GraCitas">
<div class="auth-page-wrapper">
    <div class="auth-card-container">
        <!-- Encabezado Institucional Formal -->
        <div class="auth-header">
            <div class="auth-brand-badge">
                <img src="{{ asset('images/logo.svg') }}" alt="GraCitas Logo" class="auth-brand-logo">
                <div class="auth-brand-text">
                    <span class="brand-title">Gra<span>Citas</span></span>
                    <span class="brand-tagline">Sistema de Gestión de Canchas Sintéticas</span>
                </div>
            </div>
            <h1 class="auth-title">Acceso al Sistema</h1>
            <p class="auth-subtitle">Ingrese sus credenciales registradas para acceder a su panel de gestión o realizar reservas deportivas.</p>
        </div>

        <!-- Módulo Discreto de Demostración Académica -->
        <div class="demo-acc-panel">
            <div class="demo-acc-header" onclick="toggleDemoPanel()">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <x-icon name="shield" size="14" style="color: var(--primary);" />
                    <span>Cuentas de evaluación académica (Clic para autocompletar)</span>
                </div>
                <span id="demo-toggle-icon" style="font-size: 0.75rem; color: var(--text-muted);">▼</span>
            </div>
            <div id="demo-acc-body" class="demo-acc-body" style="display: none;">
                <div class="demo-buttons-grid">
                    <button type="button" class="btn-demo-quick" onclick="setLogin('admin@gracitas.com', 'admin123')">
                        <strong>Administrador</strong>
                        <span>admin@gracitas.com</span>
                    </button>
                    <button type="button" class="btn-demo-quick" onclick="setLogin('propietario@gracitas.com', 'propietario123')">
                        <strong>Propietario</strong>
                        <span>propietario@gracitas.com</span>
                    </button>
                    <button type="button" class="btn-demo-quick" onclick="setLogin('cliente@gracitas.com', 'cliente123')">
                        <strong>Cliente</strong>
                        <span>cliente@gracitas.com</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulario Formal de Autenticación -->
        <form action="{{ route('login') }}" method="POST" class="auth-form" novalidate>
            @csrf

            <!-- Campo Correo Electrónico -->
            <div class="form-group-formal">
                <label for="email" class="form-label-formal">
                    Correo Electrónico Institucional o Personal <span class="req">*</span>
                </label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <x-icon name="mail" size="18" />
                    </span>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control-formal @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           placeholder="nombre@ejemplo.com"
                           autocomplete="email">
                </div>
                @error('email')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Campo Contraseña -->
            <div class="form-group-formal">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                    <label for="password" class="form-label-formal" style="margin-bottom: 0;">
                        Contraseña de Seguridad <span class="req">*</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="link-forgot-pass">
                        ¿Olvidó su contraseña?
                    </a>
                </div>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <x-icon name="lock" size="18" />
                    </span>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control-formal @error('password') is-invalid @enderror" 
                           required 
                           placeholder="••••••••••••"
                           autocomplete="current-password">
                    <button type="button" class="btn-toggle-pass" onclick="togglePassVisibility('password', this)" title="Mostrar u ocultar contraseña">
                        <x-icon name="eye" size="18" />
                    </button>
                </div>
                @error('password')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Opciones Adicionales -->
            <div class="form-options-row">
                <label class="remember-checkbox-label">
                    <input type="checkbox" name="remember" class="custom-checkbox" {{ old('remember') ? 'checked' : '' }}>
                    <span>Recordar sesión en este equipo</span>
                </label>
            </div>

            <!-- Botón Principal de Acceso -->
            <button type="submit" class="btn-submit-formal">
                <span>Ingresar al Sistema</span>
                <x-icon name="arrow-right" size="18" />
            </button>
        </form>

        <!-- Pie de Seguridad e Información -->
        <div class="auth-security-footer">
            <div class="security-badge">
                <x-icon name="shield" size="16" style="color: #059669;" />
                <span>Acceso protegido con cifrado SSL/TLS. La cuenta requiere verificación obligatoria por correo para garantizar la autenticidad de los usuarios.</span>
            </div>
            <div class="auth-switch-link">
                ¿Aún no dispone de una cuenta? 
                <a href="{{ route('register') }}">Registrarse formalmente</a>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-page-wrapper {
        min-height: calc(100vh - 180px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        background: radial-gradient(circle at 50% 0%, rgba(8, 127, 104, 0.08) 0%, rgba(248, 250, 252, 1) 70%);
    }

    .auth-card-container {
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        padding: 36px 32px;
        position: relative;
    }

    .auth-card-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #087f68 0%, #0d9488 50%, #10b981 100%);
        border-top-left-radius: var(--radius-lg);
        border-top-right-radius: var(--radius-lg);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .auth-brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 8px 16px;
        background: #f8fafc;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
    }

    .auth-brand-logo {
        height: 32px;
        width: auto;
    }

    .auth-brand-text {
        text-align: left;
        line-height: 1.1;
    }

    .brand-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 1.15rem;
        color: #0f172a;
    }

    .brand-title span {
        color: var(--primary);
    }

    .brand-tagline {
        display: block;
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .auth-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .auth-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.5;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Panel de Demostración */
    .demo-acc-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .demo-acc-header {
        padding: 10px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
        transition: background 0.15s ease;
    }

    .demo-acc-header:hover {
        background: #f1f5f9;
    }

    .demo-acc-body {
        padding: 10px 14px 14px;
        border-top: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .demo-buttons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .btn-demo-quick {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: var(--radius-sm);
        padding: 8px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .btn-demo-quick strong {
        font-size: 0.78rem;
        color: #0f172a;
    }

    .btn-demo-quick span {
        font-size: 0.65rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-demo-quick:hover {
        background: var(--primary-light);
        border-color: var(--primary);
    }

    .btn-demo-quick:hover strong {
        color: var(--primary);
    }

    /* Formulario Formal */
    .form-group-formal {
        margin-bottom: 20px;
    }

    .form-label-formal {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .form-label-formal .req {
        color: #dc2626;
        font-weight: 700;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        color: #64748b;
        display: flex;
        align-items: center;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .form-control-formal {
        width: 100%;
        padding: 11px 42px 11px 44px;
        font-size: 0.95rem;
        color: #0f172a;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: var(--radius-sm);
        outline: none;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-control-formal:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(8, 127, 104, 0.15);
        background: #ffffff;
    }

    .form-control-formal.is-invalid {
        border-color: #ef4444;
        background-color: #fffaf0;
    }

    .btn-toggle-pass {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: color 0.2s ease;
    }

    .btn-toggle-pass:hover {
        color: #0f172a;
    }

    .field-error-msg {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: #dc2626;
        font-weight: 500;
        margin-top: 6px;
    }

    .link-forgot-pass {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .link-forgot-pass:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .form-options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .remember-checkbox-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #475569;
        cursor: pointer;
        user-select: none;
    }

    .custom-checkbox {
        accent-color: var(--primary);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .btn-submit-formal {
        width: 100%;
        background: var(--primary-gradient);
        color: #ffffff;
        font-size: 0.98rem;
        font-weight: 700;
        padding: 13px 20px;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(8, 127, 104, 0.25);
        transition: all 0.2s ease;
    }

    .btn-submit-formal:hover {
        background: linear-gradient(135deg, #066553 0%, #0f766e 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(8, 127, 104, 0.35);
    }

    .auth-security-footer {
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
    }

    .security-badge {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm);
        padding: 10px 12px;
        font-size: 0.78rem;
        color: #166534;
        line-height: 1.4;
        text-align: left;
        margin-bottom: 18px;
    }

    .auth-switch-link {
        font-size: 0.88rem;
        color: #64748b;
    }

    .auth-switch-link a {
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
    }

    .auth-switch-link a:hover {
        text-decoration: underline;
    }
</style>

<script>
    function toggleDemoPanel() {
        const body = document.getElementById('demo-acc-body');
        const icon = document.getElementById('demo-toggle-icon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.textContent = '▲';
        } else {
            body.style.display = 'none';
            icon.textContent = '▼';
        }
    }

    function setLogin(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
        const body = document.getElementById('demo-acc-body');
        const icon = document.getElementById('demo-toggle-icon');
        body.style.display = 'none';
        icon.textContent = '▼';
    }

    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
        }
    }
</script>
</x-layouts.app>
