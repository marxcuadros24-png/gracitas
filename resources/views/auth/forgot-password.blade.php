<x-layouts.app title="Recuperar Contraseña">
<div class="container" style="max-width: 480px; padding-top: 40px; padding-bottom: 60px;">
    <div class="card" style="border-top: 4px solid var(--primary);">
        <div style="text-align: center; margin-bottom: 24px;">
            <img src="{{ asset('images/logo.svg') }}" alt="GraCitas" style="height: 60px; margin-bottom: 12px;">
            <h2>Recuperar Contraseña</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Ingresa tu correo electrónico registrado y te enviaremos un enlace seguro para restablecer tu clave de acceso.
            </p>
        </div>

        @if(session('status'))
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.9rem; display: flex; align-items: flex-start; gap: 10px;">
                <x-icon name="check" size="18" style="color: #059669; margin-top: 2px;" />
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico Registrado</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="ejemplo@gmail.com">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                <x-icon name="mail" /> Enviar Enlace de Recuperación
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border); font-size: 0.9rem;">
            ¿Recordaste tu contraseña? <a href="{{ route('login') }}" style="font-weight: 700;">Volver al inicio de sesión</a>
        </div>
    </div>
</div>
</x-layouts.app>
