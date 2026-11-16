<x-layouts.app :title="__('messages.auth.register_title') . ' - GraCitas'">
<div class="auth-page-wrapper">
    <div class="auth-card-container register-card">
        <!-- Encabezado Institucional -->
        <div class="auth-header">
            <div class="auth-brand-badge">
                <img src="{{ asset('images/logo.svg') }}" alt="GraCitas Logo" class="auth-brand-logo">
                <div class="auth-brand-text">
                    <span class="brand-title">Gra<span>Citas</span></span>
                    <span class="brand-tagline">{{ __('messages.demo_banner') }}</span>
                </div>
            </div>
            <h1 class="auth-title">{{ __('messages.auth.register_title') }}</h1>
            <p class="auth-subtitle">Complete el formulario con información verídica. Por seguridad, su dirección de correo electrónico será verificada antes del primer acceso.</p>
        </div>

        <!-- Formulario Formal de Registro -->
        <form action="{{ route('register') }}" method="POST" class="auth-form" novalidate>
            @csrf

            <!-- Selección de Perfil / Rol Formal -->
            <div class="form-group-formal">
                <label class="form-label-formal">
                    Tipo de Cuenta / Rol en la Plataforma <span class="req">*</span>
                </label>
                <div class="role-selector-cards">
                    <label class="role-card-option" id="card-rol-cliente">
                        <input type="radio" name="rol" value="cliente" {{ old('rol', 'cliente') === 'cliente' ? 'checked' : '' }} onchange="updateRoleSelection(this.value)">
                        <div class="role-card-content">
                            <div class="role-card-header">
                                <span class="role-icon">
                                    <x-icon name="user" size="18" />
                                </span>
                                <span class="role-name">Cliente / Deportista</span>
                            </div>
                            <p class="role-desc">Para personas o equipos que desean consultar disponibilidad y reservar canchas sintéticas en Coracora.</p>
                        </div>
                    </label>

                    <label class="role-card-option" id="card-rol-propietario">
                        <input type="radio" name="rol" value="propietario" {{ old('rol') === 'propietario' ? 'checked' : '' }} onchange="updateRoleSelection(this.value)">
                        <div class="role-card-content">
                            <div class="role-card-header">
                                <span class="role-icon">
                                    <x-icon name="building" size="18" />
                                </span>
                                <span class="role-name">Propietario / Complejo</span>
                            </div>
                            <p class="role-desc">Para administradores o dueños de locales deportivos que publicarán canchas, horarios y cobros.</p>
                        </div>
                    </label>
                </div>
                @error('rol')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nombres Completos -->
            <div class="form-group-formal">
                <label for="name" class="form-label-formal">
                    {{ __('messages.auth.name') }} <span class="req">*</span>
                </label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <x-icon name="user" size="18" />
                    </span>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control-formal @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Ej. Juan Carlos Huamaní Gómez"
                           autocomplete="name">
                </div>
                @error('name')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Correo Electrónico Verificable -->
            <div class="form-group-formal">
                <label for="email" class="form-label-formal">
                    {{ __('messages.auth.email') }} <span class="req">*</span>
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
                           placeholder="nombre.apellido@gmail.com"
                           autocomplete="email">
                </div>
                <div class="field-hint">
                    <x-icon name="info" size="13" /> Ingrese un correo existente (Gmail, Outlook, Yahoo, corporativo). Se validará el dominio y se enviará un enlace de verificación obligatorio.
                </div>
                @error('email')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Teléfono Celular -->
            <div class="form-group-formal">
                <label for="telefono" class="form-label-formal">
                    {{ __('messages.auth.phone') }} <span class="optional">(Opcional)</span>
                </label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <x-icon name="phone" size="18" />
                    </span>
                    <input type="tel" 
                           id="telefono" 
                           name="telefono" 
                           class="form-control-formal @error('telefono') is-invalid @enderror" 
                           value="{{ old('telefono') }}" 
                           placeholder="Ej. 987 654 321"
                           autocomplete="tel">
                </div>
                @error('telefono')
                    <div class="field-error-msg">
                        <x-icon name="alert" size="14" /> {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Contraseñas -->
            <div class="password-grid-row">
                <div class="form-group-formal" style="margin-bottom: 0;">
                    <label for="password" class="form-label-formal">
                        {{ __('messages.auth.password') }} <span class="req">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">
                            <x-icon name="lock" size="18" />
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control-formal @error('password') is-invalid @enderror" 
                               required 
                               placeholder="Mínimo 6 caracteres"
                               autocomplete="new-password">
                        <button type="button" class="btn-toggle-pass" onclick="togglePassVisibility('password', this)">
                            <x-icon name="eye" size="18" />
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error-msg">
                            <x-icon name="alert" size="14" /> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-formal" style="margin-bottom: 0;">
                    <label for="password_confirmation" class="form-label-formal">
                        {{ __('messages.auth.confirm_password') }} <span class="req">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">
                            <x-icon name="lock" size="18" />
                        </span>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control-formal" 
                               required 
                               placeholder="Repita la contraseña"
                               autocomplete="new-password">
                        <button type="button" class="btn-toggle-pass" onclick="togglePassVisibility('password_confirmation', this)">
                            <x-icon name="eye" size="18" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Aviso de Verificación y Términos -->
            <div class="register-policy-box">
                <x-icon name="shield" size="18" style="color: var(--primary); flex-shrink: 0; margin-top: 2px;" />
                <div>
                    <strong>Compromiso de Autenticidad:</strong> Al registrarse, declara que los datos ingresados le pertenecen y acepta que la plataforma mantendrá restringido el acceso a las funciones hasta confirmar el enlace remitido a su correo.
                </div>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="btn-submit-formal" style="margin-top: 10px;">
                <span>{{ __('messages.nav.register') }}</span>
                <x-icon name="arrow-right" size="18" />
            </button>
        </form>

        <!-- Pie de Página -->
        <div class="auth-security-footer">
            <div class="auth-switch-link">
                {{ __('messages.auth.already_registered') }} 
                <a href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
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

    .auth-card-container.register-card {
        max-width: 580px;
        width: 100%;
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        padding: 38px 34px;
        position: relative;
    }

    .auth-card-container.register-card::before {
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
        font-size: 0.88rem;
        color: var(--text-muted);
        line-height: 1.5;
        max-width: 460px;
        margin: 0 auto;
    }

    /* Selector de Rol en Tarjetas */
    .role-selector-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 6px;
    }

    .role-card-option {
        display: block;
        position: relative;
        cursor: pointer;
    }

    .role-card-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .role-card-content {
        padding: 14px;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
        height: 100%;
    }

    .role-card-option:hover .role-card-content {
        border-color: var(--primary);
        background: #f0fdfa;
    }

    .role-card-option input[type="radio"]:checked + .role-card-content {
        border-color: var(--primary);
        background: #f0fdfa;
        box-shadow: 0 0 0 2px rgba(8, 127, 104, 0.2);
    }

    .role-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .role-icon {
        color: var(--primary);
        display: flex;
        align-items: center;
    }

    .role-name {
        font-weight: 700;
        font-size: 0.88rem;
        color: #0f172a;
    }

    .role-desc {
        font-size: 0.76rem;
        color: var(--text-muted);
        line-height: 1.4;
        margin: 0;
    }

    /* Formulario Formal */
    .form-group-formal {
        margin-bottom: 18px;
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

    .form-label-formal .optional {
        color: #64748b;
        font-size: 0.78rem;
        font-weight: normal;
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
        padding: 10px 42px 10px 42px;
        font-size: 0.92rem;
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

    .field-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 5px;
        display: flex;
        align-items: flex-start;
        gap: 5px;
        line-height: 1.35;
    }

    .field-error-msg {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #dc2626;
        font-weight: 500;
        margin-top: 5px;
    }

    .password-grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 18px;
    }

    .register-policy-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        font-size: 0.78rem;
        color: #475569;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        line-height: 1.45;
        margin-bottom: 20px;
    }

    .register-policy-box strong {
        color: #0f172a;
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

    @media (max-width: 600px) {
        .role-selector-cards {
            grid-template-columns: 1fr;
        }
        .password-grid-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .auth-card-container.register-card {
            padding: 28px 20px;
        }
    }
</style>

<script>
    function updateRoleSelection(val) {
        // La actualización de estilo se maneja nativamente por CSS mediante :checked
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
