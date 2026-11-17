<x-layouts.app title="Certificado Oficial de Auditoría de Seguridad — GraCitas">
    <div class="container" style="max-width: 920px; padding: 40px 16px;">
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="arrow-left" size="14" /> {{ __('messages.canchas.back') }}
            </a>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('seguridad.auditoria') }}" class="btn btn-primary btn-sm" target="_blank" style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="download" size="14" /> Informe de Auditoría (PDF)
                </a>
                <a href="{{ route('seguridad.remediaciones') }}" class="btn btn-outline btn-sm" target="_blank" style="display: inline-flex; align-items: center; gap: 6px;">
                    <x-icon name="file-text" size="14" /> Seguimiento de Remediaciones (PDF)
                </a>
            </div>
        </div>

        <!-- Tarjeta Oficial del Certificado -->
        <div class="card" style="padding: clamp(28px, 5vw, 48px); border-top: 6px solid #10b981; box-shadow: 0 20px 45px rgba(0,0,0,0.08); background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-radius: var(--radius-lg); position: relative; overflow: hidden;">
            <!-- Marca de agua de fondo -->
            <div style="position: absolute; right: -40px; top: -40px; width: 260px; height: 260px; background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

            <!-- Cabecera del Certificado con Sello -->
            <div style="text-align: center; border-bottom: 2px dashed #cbd5e1; padding-bottom: 30px; margin-bottom: 32px;">
                <!-- Escudo de Seguridad -->
                <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: linear-gradient(135deg, #065f46 0%, #10b981 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35); border: 4px solid #ecfdf5;">
                    <x-icon name="shield" size="40" />
                </div>

                <span style="display: inline-block; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 4px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">
                    Certificación de Seguridad de la Información
                </span>

                <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.4rem); font-weight: 900; color: #0f172a; margin-bottom: 8px;">
                    Certificado de Auditoría de Seguridad &amp; Remediación
                </h1>
                <p style="color: #64748b; font-size: 1rem; max-width: 650px; margin: 0 auto;">
                    Plataforma Web E-Commerce y Marketplace de Reserva de Canchas Sintéticas <strong>GraCitas</strong>
                </p>

                <!-- Badge de Dictamen -->
                <div style="display: inline-flex; align-items: center; gap: 10px; margin-top: 18px; background: #064e3b; color: #ffffff; padding: 8px 22px; border-radius: 9999px; box-shadow: 0 4px 14px rgba(6, 78, 59, 0.3);">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                    <span style="font-weight: 800; font-size: 0.95rem; letter-spacing: 0.5px;">
                        DICTAMEN: APROBADO CON EXCELENCIA (GRADO A+)
                    </span>
                </div>
            </div>

            <!-- Ficha Técnica de la Auditoría -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 32px;">
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Código de Certificado</div>
                    <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem;">CERT-SEC-GRACITAS-2026-A1</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Fecha de Dictamen</div>
                    <div style="font-weight: 800; color: #0f172a; font-size: 1.05rem;">Noviembre 2026</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Vulnerabilidades Críticas</div>
                    <div style="font-weight: 800; color: #16a34a; font-size: 1.05rem;">0 (Cero)</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Estado de Mitigación</div>
                    <div style="font-weight: 800; color: #16a34a; font-size: 1.05rem;">100% Remediado</div>
                </div>
            </div>

            <!-- Declaración de Certificación -->
            <div style="color: #334155; font-size: 0.95rem; line-height: 1.7; display: flex; flex-direction: column; gap: 24px;">
                <p>
                    Se certifica que la plataforma web <strong>GraCitas</strong> (versión v2.4.1), desarrollada con el framework <strong>Laravel</strong> y lenguaje <strong>PHP 8.5</strong>, ha sido sometida a un riguroso proceso de <strong>Auditoría de Seguridad de Aplicaciones Web</strong> y posterior <strong>Verificación de Remediación Integral</strong>, concluyendo con la mitigación absoluta de todas las no conformidades identificadas.
                </p>

                <!-- Tabla de Controles Verificados -->
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <x-icon name="check-circle" size="18" /> Controles de Seguridad Remediados y Certificados
                    </h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                            <thead>
                                <tr style="background: #f1f5f9; text-align: left;">
                                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #1e293b;">Control / Vulnerabilidad</th>
                                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #1e293b;">Riesgo Previo</th>
                                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #1e293b;">Mecanismo de Remediación Implementado</th>
                                    <th style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 800; color: #1e293b;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Control IDOR en Comprobantes de Pago</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #dc2626; font-weight: 700;">Alto</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Validación estricta de autorización: solo el cliente titular y el dueño del establecimiento acceden a la descarga/vista del recibo.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr style="background: #f8fafc;">
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Anti-Overbooking Concurrente</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #dc2626; font-weight: 700;">Alto</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Bloqueo pesimista de base de datos (<code>lockForUpdate</code>) durante la transacción de creación de reserva.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Fuga de Códigos en Disponibilidad Pública</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #d97706; font-weight: 700;">Medio</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Sanitización de endpoints públicos eliminando códigos privados de reserva del JSON de slots ocupados.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr style="background: #f8fafc;">
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Anonimización Contable de Usuarios</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #d97706; font-weight: 700;">Medio</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Anonimización criptográfica al solicitar eliminación de cuenta, conservando la integridad de reportes de caja.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Re-verificación de Correo Electrónico</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #d97706; font-weight: 700;">Medio</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Revocación automática de <code>email_verified_at</code> ante cambio de dirección con enlace firmado de confirmación.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr style="background: #f8fafc;">
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Zona Horaria y Sincronización</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #2563eb; font-weight: 700;">Bajo</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Configuración estricta de <code>America/Lima</code> en app y validación de margen de 2 horas para cancelaciones.</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Mitigado</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; font-weight: 700;">Autenticación JWT &amp; Rate Limiting</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 700;">Preventivo</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1;">Tokens HMAC SHA-256 con revocación por lista negra y limitación de tasa por IP/cuenta (HTTP 429).</td>
                                    <td style="padding: 10px 12px; border: 1px solid #cbd5e1; color: #16a34a; font-weight: 800;">✓ Activo</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Garantías Legales y Técnicas -->
                <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 18px; border-radius: 6px;">
                    <strong style="color: #065f46; font-size: 0.95rem;">Garantía de Confianza para Clientes y Dueños de Canchas:</strong>
                    <p style="margin-top: 6px; font-size: 0.88rem; color: #047857; margin-bottom: 0;">
                        Los datos de reserva, teléfonos de contacto y registros de cobro están protegidos bajo los lineamientos de la <strong>Ley N° 29733 (Ley de Protección de Datos Personales del Perú)</strong> y el código penal en materia de delitos informáticos (Ley N° 30096). Ningún tercero no autorizado puede visualizar ni alterar horarios o reservas ajenas.
                    </p>
                </div>

                <!-- Botones de Descarga y Firma -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 10px; flex-wrap: wrap; gap: 16px;">
                    <div style="font-size: 0.85rem; color: #64748b;">
                        <div><strong>Emitido para:</strong> GraCitas Coracora — Sistema de Gestión Deportiva</div>
                        <div><strong>Validador:</strong> Automated Security Audit Suite &amp; Code Review Framework</div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('seguridad.auditoria') }}" class="btn btn-primary" target="_blank" style="padding: 10px 20px; font-weight: 700;">
                            <x-icon name="download" size="16" /> Descargar Auditoría (PDF)
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 10px 20px;">
                            Ir a la Landing Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
