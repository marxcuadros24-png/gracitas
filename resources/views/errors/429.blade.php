<x-layouts.app title="429 - Límite de Peticiones Excedido">
    <div class="container" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px;">
        <div class="card" style="max-width: 520px; width: 100%; text-align: center; padding: 40px 32px; border-top: 4px solid #f59e0b; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);">
            <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 28px;">
                ⏱️
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text); margin-bottom: 8px;">
                Demasiadas Solicitudes (429)
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; margin-bottom: 24px;">
                Has realizado demasiadas solicitudes en un periodo de tiempo muy corto. Por motivos de seguridad y para garantizar la disponibilidad del servicio para toda la comunidad de Coracora, tu acceso ha sido pausado temporalmente.
            </p>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 24px; font-size: 0.85rem; color: #64748b;">
                Por favor, espera unos momentos antes de recargar la página o volver a intentar.
            </div>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 10px 24px;">
                    Volver al Inicio
                </a>
                <button onclick="window.history.back()" class="btn btn-outline" style="padding: 10px 24px;">
                    Regresar
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>

