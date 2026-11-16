<x-layouts.app title="Políticas de Privacidad y Tratamiento de Datos">
    <div class="container" style="max-width: 860px; padding: 40px 16px;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="arrow-left" size="14" /> {{ __('messages.canchas.back') }}
            </a>
        </div>

        <div class="card" style="padding: 36px 32px; border-top: 4px solid #0284c7;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 48px; height: 48px; border-radius: 10px; background: #f0f9ff; display: flex; align-items: center; justify-content: center; color: #0284c7;">
                    <x-icon name="shield" size="26" />
                </div>
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text);">Políticas de Privacidad y Datos Personales</h1>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">GraCitas Coracora — Conforme a la Ley N° 29733 (Perú)</p>
                </div>
            </div>

            <div style="color: #334155; font-size: 0.95rem; line-height: 1.7; display: flex; flex-direction: column; gap: 20px;">
                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">1. Compromiso con la Privacidad</h3>
                    <p>
                        En <strong>GraCitas</strong>, garantizamos la seguridad, confidencialidad y tratamiento ético de los datos personales de nuestros deportistas, clientes y socios comerciales en estricto cumplimiento con la <strong>Ley de Protección de Datos Personales del Perú (Ley N° 29733)</strong> y su reglamento (D.S. 003-2013-JUS).
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">2. Datos Recopilados</h3>
                    <p>
                        Para procesar las reservas deportivas y emitir comprobantes digitales, recopilamos únicamente los datos necesarios:
                    </p>
                    <ul style="padding-left: 20px; margin-top: 6px;">
                        <li>Nombre y apellidos del organizador o jugador.</li>
                        <li>Dirección de correo electrónico para verificación de cuenta y confirmaciones.</li>
                        <li>Número de teléfono celular para recordatorios de partido vía WhatsApp / SMS.</li>
                        <li>Historial de partidos y comprobantes de reserva emitidos.</li>
                    </ul>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">3. Finalidad del Tratamiento</h3>
                    <p>
                        Los datos se utilizan exclusivamente para:
                    </p>
                    <ul style="padding-left: 20px; margin-top: 6px;">
                        <li>Generar y sincronizar en vivo las reservas en los complejos deportivos afiliados.</li>
                        <li>Enviar notificaciones y comprobantes digitales oficiales.</li>
                        <li>Prevenir fraudes, colisiones horarias y accesos malintencionados mediante Rate Limiting y JWT.</li>
                        <li>Permitir a los dueños de locales registrar los cobros presenciales correspondientes.</li>
                    </ul>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">4. Seguridad Tecnológica y Criptografía</h3>
                    <p>
                        Implementamos arquitectura de seguridad con cifrado de contraseñas mediante algoritmo Bcrypt, tokens de sesión criptográficos JWT (HS256), protección contra ataques de fuerza bruta (Rate Limiting) y canales privados WebSockets cifrados.
                    </p>
                </section>

                <section>
                    <h3 style="color: #0f172a; margin-bottom: 6px; font-size: 1.15rem;">5. Derechos ARCO</h3>
                    <p>
                        Los usuarios pueden ejercer en cualquier momento sus derechos de Acceso, Rectificación, Cancelación y Oposición (ARCO) comunicándose al canal de soporte oficial o editando su información en el apartado "Mi Perfil".
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
