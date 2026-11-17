<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * Verifica que la cabecera (Hero) contenga el titular, subtítulo y CTA exactos de Mobile Marketing.
     */
    public function test_landing_page_renders_with_mobile_marketing_hero_and_copy(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Reserva tu cancha sintética en Coracora en menos de 1 minuto.')
            ->assertSee('Encuentra disponibilidad en tiempo real, compara precios y asegura tu partido sin llamadas largas.')
            ->assertSee('Ver Canchas Disponibles');
    }

    /**
     * Verifica los 3 pasos visuales del flujo de reserva simple.
     */
    public function test_landing_page_displays_how_it_works_three_steps(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('1. Elige tu cancha y horario')
            ->assertSee('2. Paga tu reserva (Total o abono) de forma segura')
            ->assertSee('¡Recibe tu confirmación y a jugar!');
    }

    /**
     * Verifica la sección del catálogo E-Commerce con tarjetas de canchas populares.
     */
    public function test_landing_page_includes_popular_pitches_ecommerce_catalog(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Catálogo de Canchas Populares')
            ->assertSee('Reservar Ahora');
    }

    /**
     * Verifica la sección de prueba social y la reseña clave de Inbound Marketing.
     */
    public function test_landing_page_displays_social_proof_and_exact_testimonial(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Lo que dicen los capitanes y futbolistas')
            ->assertSee('Desde que usamos GraCitas, mi equipo ya no pierde tiempo buscando dónde jugar el fin de semana.')
            ->assertSee('Carlos Mendoza');
    }

    /**
     * Verifica la sección de captación B2B para propietarios de canchas.
     */
    public function test_landing_page_includes_b2b_partner_acquisition_section(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('¿Eres dueño de una cancha?')
            ->assertSee('Aumenta tus reservas y gestiona tus horarios de forma automatizada.')
            ->assertSee('Únete como Partner');
    }

    /**
     * Verifica el pie de página con pasarelas de pago (IziPay, Niubiz, Yape, Plin) y cumplimiento legal.
     */
    public function test_landing_page_footer_contains_legal_and_payment_gateways(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Términos y Condiciones')
            ->assertSee('Políticas de Privacidad')
            ->assertSee('Libro de Reclamaciones')
            ->assertSee('Yape')
            ->assertSee('Plin')
            ->assertSee('IziPay')
            ->assertSee('Niubiz');
    }

    /**
     * Verifica que las páginas legales de Términos y Privacidad respondan 200 OK.
     */
    public function test_legal_pages_are_accessible(): void
    {
        $this->get(route('terminos'))
            ->assertOk()
            ->assertSee('Términos y Condiciones del Servicio')
            ->assertSee('Coracora');

        $this->get(route('privacidad'))
            ->assertOk()
            ->assertSee('Políticas de Privacidad y Datos Personales')
            ->assertSee('Ley N° 29733');
    }

    /**
     * Verifica que la landing page incluya la sección y badges del Certificado de Auditoría de Seguridad.
     */
    public function test_landing_page_includes_security_audit_certificate_section(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Auditoría de Seguridad Aprobada')
            ->assertSee('Certificado de Auditoría de Seguridad')
            ->assertSee('Confianza Digital')
            ->assertSee('Control IDOR Blindado')
            ->assertSee('Anti-Overbooking Pesimista')
            ->assertSee('CALIFICACIÓN A+')
            ->assertSee('Ver Certificado Oficial');
    }

    /**
     * Verifica que la página del Certificado de Seguridad y las rutas de descarga de informes respondan correctamente.
     */
    public function test_security_audit_certificate_page_and_pdf_routes_are_accessible(): void
    {
        $this->get(route('seguridad.certificado'))
            ->assertOk()
            ->assertSee('Certificado de Auditoría de Seguridad &amp; Remediación', false)
            ->assertSee('CERT-SEC-GRACITAS-2026-A1')
            ->assertSee('DICTAMEN: APROBADO CON EXCELENCIA (GRADO A+)')
            ->assertSee('0 (Cero)');

        $this->get(route('seguridad.auditoria'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->get(route('seguridad.remediaciones'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }
}
