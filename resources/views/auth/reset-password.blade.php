<x-layouts.app title="Restablecer Contraseña">
<div class="container" style="max-width: 480px; padding-top: 40px; padding-bottom: 60px;">
    <div class="card" style="border-top: 4px solid var(--primary);">
        <div style="text-align: center; margin-bottom: 24px;">
            <img src="{{ asset('images/logo.svg') }}" alt="GraCitas" style="height: 60px; margin-bottom: 12px;">
            <h2>Nueva Contraseña</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Crea una contraseña segura para tu cuenta en GraCitas.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $email) }}" required autofocus placeholder="ejemplo@gmail.com">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Mínimo 6 caracteres">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Repite la nueva contraseña">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                <x-icon name="check" /> Guardar Nueva Contraseña
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border); font-size: 0.9rem;">
            <a href="{{ route('login') }}" style="font-weight: 700;">Volver al inicio de sesión</a>
        </div>
    </div>
</div>
</x-layouts.app>
