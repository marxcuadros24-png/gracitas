<x-layouts.app title="Términos y Condiciones del Servicio">
    <div class="container" style="max-width: 860px; padding: 40px 16px;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="arrow-left" size="14" /> {{ __('messages.canchas.back') }}
            </a>
        </div>

        <div class="card" style="padding: 36px 32px; border-top: 4px solid var(--primary);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 48px; height: 48px; border-radius: 10px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <x-icon name="file-text" size="26" />
                </div>
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text);">Términos y Condiciones del Servicio</h1>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">GraCitas Coracora — Última actualización: Noviembre 2026</p>
                </div>
            </div>

            <div style="color: #334155; font-size: 0.95rem; line-height: 1.7; display: flex; flex-direction: column; gap: 20px;">
                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">1. Objeto y Alcance de la Plataforma</h3>
                    <p>
                        <strong>GraCitas</strong> es una plataforma tecnológica e-commerce y marketplace orientada a la intermediación y reserva en tiempo real de canchas de fútbol con gras sintético en la ciudad de <strong>Coracora, Provincia de Parinacochas, Ayacucho, Perú</strong>. Su propósito es conectar de forma ágil a deportistas y organizadores de partidos (usuarios B2C) con los propietarios o administradores de complejos deportivos locales (partners B2B).
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">2. Registro y Responsabilidad de Cuenta</h3>
                    <p>
                        Para apartar horarios, el usuario debe crear una cuenta oficial consignando datos verídicos (nombre completo, correo electrónico y número de celular de contacto). El usuario es responsable de mantener la confidencialidad de sus credenciales.
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">3. Disponibilidad en Vivo y Confirmación de Horarios</h3>
                    <p>
                        El sistema sincroniza turnos en vivo mediante WebSockets (Laravel Reverb). Al seleccionar un horario disponible y confirmar la reserva, dicho bloque queda apartado de forma inmediata e intransferible, emitiendo un <strong>Comprobante Digital con Código Único</strong>.
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">4. Mecanismo de Pagos y Pasarelas</h3>
                    <p>
                        GraCitas soporta modalidades de pago adaptadas a la región: pagos presenciales en ventanilla al momento del partido (Efectivo, Yape, Plin) e integraciones con pasarelas de pago digitales (IziPay / Niubiz para tarjetas de débito y crédito). El monto o abono mínimo por hora es fijado libremente por el propietario del establecimiento.
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">5. Políticas de Cancelación y Tolerancia</h3>
                    <p>
                        Los partidos deben iniciarse puntualmente. Se otorga una tolerancia máxima de 15 minutos en el establecimiento. Las cancelaciones pueden realizarse desde el portal del cliente con al menos 2 horas de anticipación para permitir que otro equipo de fútbol de la comunidad tome el horario liberado.
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">6. Normativa Legal Aplicable</h3>
                    <p>
                        Este acuerdo se rige bajo la legislación peruana vigente, incluyendo el <strong>Código de Protección y Defensa del Consumidor (Ley N° 29571)</strong> y las normas aplicables al comercio electrónico en la República del Perú.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
