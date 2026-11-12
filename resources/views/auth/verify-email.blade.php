<x-layouts.app title="Verificación Obligatoria de Correo">
<div class="container" style="max-width: 560px; padding-top: 40px; padding-bottom: 60px;">
    <div class="card" style="border-top: 4px solid var(--primary); text-align: center;">
        <div style="margin-bottom: 20px;">
            <div style="width: 68px; height: 68px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <x-icon name="mail" size="34" />
            </div>
            <h2>Verificación de Correo Requerida</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 8px; line-height: 1.5;">
                Por motivos de seguridad, debes confirmar tu cuenta antes de acceder a la plataforma. Hemos enviado un enlace de activación a:
            </p>
            <div style="font-weight: 700; color: #0f172a; font-size: 1.1rem; margin-top: 6px; word-break: break-all; background: #f1f5f9; padding: 6px 12px; border-radius: var(--radius-sm); display: inline-block;">
                {{ auth()->user()->email }}
            </div>
        </div>

        @if(session('warning'))
            <div style="background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.9rem; text-align: left; display: flex; align-items: flex-start; gap: 10px;">
                <x-icon name="warning" size="18" style="color: #d97706; margin-top: 2px; flex-shrink: 0;" />
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        @if(session('status'))
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.9rem; text-align: left; display: flex; align-items: flex-start; gap: 10px;">
                <x-icon name="check" size="18" style="color: #059669; margin-top: 2px; flex-shrink: 0;" />
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 24px; text-align: left; font-size: 0.875rem; color: #475569; line-height: 1.6;">
            <strong style="color: #0f172a; display: block; margin-bottom: 4px;">Instrucciones para activar tu cuenta:</strong>
            <ol style="margin-left: 20px; margin-top: 6px;">
                <li>Revisa tu bandeja de entrada en Gmail u otro proveedor.</li>
                <li>Abre el correo enviado por <strong>GraCitas Coracora</strong>.</li>
                <li>Haz clic en el botón <strong>"Verificar Dirección de Correo"</strong>.</li>
                <li>Si no lo encuentras, revisa tu carpeta de <em>Spam</em> o pulsa el botón de abajo para reenviarlo.</li>
            </ol>
        </div>

        <!-- Formulario de Reenvío de Correo -->
        <form action="{{ route('verification.send') }}" method="POST" style="margin-bottom: 16px;">
            @csrf
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                <x-icon name="mail" /> Reenviar Correo de Verificación
            </button>
        </form>

        <!-- Corrección de Correo Inline si hubo equivocación -->
        <div style="margin-top: 10px; border-top: 1px solid var(--border); padding-top: 16px;">
            <button type="button" 
                    onclick="document.getElementById('edit-email-section').style.display = document.getElementById('edit-email-section').style.display === 'none' ? 'block' : 'none'" 
                    class="btn btn-outline btn-sm" 
                    style="margin-bottom: 12px;">
                <x-icon name="edit" size="14" /> ¿Te equivocaste de correo al registrarte? Corregirlo aquí
            </button>

            <div id="edit-email-section" style="display: {{ $errors->has('email') ? 'block' : 'none' }}; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 16px; text-align: left;">
                <form action="{{ route('verification.update-email') }}" method="POST">
                    @csrf
                    <label for="email" class="form-label" style="font-size: 0.85rem;">Nueva dirección de correo electrónico:</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="form-control" 
                           value="{{ old('email', auth()->user()->email) }}" 
                           required 
                           placeholder="ejemplo@gmail.com" 
                           style="margin-bottom: 8px;">
                    @error('email')
                        <div class="form-error" style="margin-bottom: 10px;">{{ $message }}</div>
                    @enderror

                    <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 10px;">
                        <button type="button" 
                                onclick="document.getElementById('edit-email-section').style.display = 'none'" 
                                class="btn btn-outline btn-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Guardar y Reenviar Enlace
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botón para Cerrar Sesión -->
        <div style="display: flex; justify-content: center; align-items: center; margin-top: 12px; font-size: 0.875rem;">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" style="color: #991b1b; border-color: #fca5a5;">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
