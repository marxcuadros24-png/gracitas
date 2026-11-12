<?php

namespace Tests\Feature;

use App\Events\DisponibilidadCanchaActualizada;
use App\Events\ReservaPropietarioNotificada;
use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Reserva;
use App\Models\User;
use App\Services\RealtimeNotificationService;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ReverbBroadcastTest extends TestCase
{
    use DatabaseTransactions;

    public function test_disponibilidad_cancha_actualizada_broadcasts_on_public_channel(): void
    {
        $event = new DisponibilidadCanchaActualizada(
            canchaId: 12,
            fecha: '2026-11-15',
            accion: 'creada',
            mensaje: 'Horario ocupado en tiempo real',
        );

        $channels = $event->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertInstanceOf(Channel::class, $channels[0]);
        $this->assertSame('cancha.12', $channels[0]->name);
        $this->assertSame('disponibilidad.actualizada', $event->broadcastAs());
    }

    public function test_reserva_propietario_notificada_broadcasts_on_private_channel(): void
    {
        $event = new ReservaPropietarioNotificada(
            establecimientoId: 5,
            codigo: 'GC-2611-TEST1',
            canchaNombre: 'Cancha Principal',
            clienteNombre: 'Juan Perez',
            inicio: '15/11/2026 18:00',
            precioTotal: 80.0,
            accion: 'creada',
            mensaje: 'Nueva reserva recibida',
        );

        $channels = $event->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame('private-establecimiento.5', $channels[0]->name);
        $this->assertSame('reserva.notificada', $event->broadcastAs());
    }

    public function test_broadcasting_auth_allows_owner_and_admin_and_blocks_other_users(): void
    {
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.key' => 'test-key',
            'broadcasting.connections.reverb.secret' => 'test-secret',
            'broadcasting.connections.reverb.app_id' => '123456',
        ]);
        Broadcast::forgetDrivers();
        require base_path('routes/channels.php');

        $propietario = User::factory()->create([
            'rol' => 'propietario',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $otroUsuario = User::factory()->create([
            'rol' => 'propietario',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $admin = User::factory()->create([
            'rol' => 'admin',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $establecimiento = Establecimiento::create([
            'propietario_id' => $propietario->id,
            'nombre' => 'Complejo Prueba Reverb',
            'direccion' => 'Av. Principal 100',
            'activo' => 1,
        ]);

        $channelName = 'private-establecimiento.'.$establecimiento->id;

        $this->actingAs($propietario)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '1234.5678',
                'channel_name' => $channelName,
            ])
            ->assertOk()
            ->assertJsonStructure(['auth']);

        $this->actingAs($admin)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '1234.5678',
                'channel_name' => $channelName,
            ])
            ->assertOk()
            ->assertJsonStructure(['auth']);

        $this->actingAs($otroUsuario)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '1234.5678',
                'channel_name' => $channelName,
            ])
            ->assertForbidden();
    }

    public function test_realtime_notification_service_dispatches_both_events(): void
    {
        Event::fake([
            DisponibilidadCanchaActualizada::class,
            ReservaPropietarioNotificada::class,
        ]);

        $propietario = User::factory()->create([
            'rol' => 'propietario',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $cliente = User::factory()->create([
            'rol' => 'cliente',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $establecimiento = Establecimiento::create([
            'propietario_id' => $propietario->id,
            'nombre' => 'Complejo Evento',
            'direccion' => 'Jr. Deportes 55',
            'activo' => 1,
        ]);

        $cancha = Cancha::create([
            'establecimiento_id' => $establecimiento->id,
            'nombre' => 'Cancha Norte',
            'capacidad' => 7,
            'precio_hora' => 90.00,
            'duracion_reserva_min' => 60,
            'activa' => 1,
        ]);

        $reserva = new Reserva([
            'codigo' => 'GC-2611-EVT01',
            'cliente_id' => $cliente->id,
            'cancha_id' => $cancha->id,
            'inicio' => Carbon::parse('2026-11-20 18:00:00', 'America/Lima'),
            'fin' => Carbon::parse('2026-11-20 19:00:00', 'America/Lima'),
            'precio_hora' => 90.00,
            'precio_total' => 90.00,
            'estado' => 'confirmada',
        ]);
        $reserva->setRelation('cancha', $cancha->setRelation('establecimiento', $establecimiento));
        $reserva->setRelation('cliente', $cliente);

        app(RealtimeNotificationService::class)->notificarCambioReserva(
            $reserva,
            'creada',
            'Nueva reserva registrada',
            'Horarios actualizados',
        );

        Event::assertDispatched(DisponibilidadCanchaActualizada::class, function (DisponibilidadCanchaActualizada $event) use ($cancha): bool {
            return $event->canchaId === $cancha->id
                && $event->fecha === '2026-11-20'
                && $event->accion === 'creada';
        });

        Event::assertDispatched(ReservaPropietarioNotificada::class, function (ReservaPropietarioNotificada $event) use ($establecimiento): bool {
            return $event->establecimientoId === $establecimiento->id
                && $event->codigo === 'GC-2611-EVT01'
                && $event->precioTotal === 90.0;
        });
    }

    public function test_cancha_detalle_renders_reverb_echo_listener(): void
    {
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.key' => 'test-key',
            'broadcasting.connections.reverb.options.host' => 'localhost',
            'broadcasting.connections.reverb.options.port' => 8080,
            'broadcasting.connections.reverb.options.scheme' => 'http',
        ]);

        $propietario = User::factory()->create([
            'rol' => 'propietario',
            'activo' => 1,
            'email_verified_at' => now(),
        ]);

        $establecimiento = Establecimiento::create([
            'propietario_id' => $propietario->id,
            'nombre' => 'Complejo Vista',
            'direccion' => 'Av. Estadio 200',
            'activo' => 1,
        ]);

        $cancha = Cancha::create([
            'establecimiento_id' => $establecimiento->id,
            'nombre' => 'Cancha Live',
            'capacidad' => 5,
            'precio_hora' => 70.00,
            'duracion_reserva_min' => 60,
            'activa' => 1,
        ]);

        $this->get(route('canchas.detalle', $cancha->id))
            ->assertOk()
            ->assertSee('Disponibilidad en vivo')
            ->assertSee("broadcaster: 'reverb'", false)
            ->assertSee('.disponibilidad.actualizada', false);
    }
}
