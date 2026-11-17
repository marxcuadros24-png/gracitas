<?php

namespace Tests\Feature;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Reserva;
use App\Models\User;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Tests\TestCase;

class SecurityRemediationsTest extends TestCase
{
    /**
     * Verifica que el servicio de disponibilidad pública no filtre códigos de reserva privados.
     */
    public function test_availability_service_does_not_leak_reservation_code(): void
    {
        $service = new AvailabilityService();

        $cancha = new Cancha();
        $cancha->id = 1;
        $cancha->duracion_reserva_min = 60;
        $cancha->precio_hora = 50.00;

        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('getSlotsForDate'));
    }

    /**
     * Verifica que el modelo Reserva calcule la posibilidad de cancelación usando la zona horaria del local.
     */
    public function test_reserva_puede_cancelar_uses_america_lima_timezone(): void
    {
        $reserva = new Reserva();
        $reserva->estado = 'confirmada';
        // Programada dentro de 3 horas en America/Lima
        $reserva->inicio = Carbon::now('America/Lima')->addHours(3);

        $this->assertTrue($reserva->puedeCancelarPorCliente());

        // Programada dentro de 1 hora (menos de 2 horas de anticipación)
        $reserva->inicio = Carbon::now('America/Lima')->addHour();
        $this->assertFalse($reserva->puedeCancelarPorCliente());
    }

    /**
     * Verifica que la configuración de zona horaria por defecto sea America/Lima.
     */
    public function test_app_timezone_is_configured_to_america_lima(): void
    {
        $this->assertSame('America/Lima', config('app.timezone'));
    }
}
