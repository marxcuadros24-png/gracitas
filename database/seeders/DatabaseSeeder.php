<?php

namespace Database\Seeders;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\HorarioAtencion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuarios Administrador, Propietarios y Clientes
        $admin = User::firstOrCreate(
            ['email' => 'admin@gracitas.com'],
            [
                'name' => 'Administrador GraCitas',
                'password' => Hash::make('admin123'),
                'telefono' => '966123456',
                'rol' => 'admin',
                'activo' => 1,
                'email_verified_at' => now(),
            ]
        );

        $prop1 = User::firstOrCreate(
            ['email' => 'propietario@gracitas.com'],
            [
                'name' => 'Alberto Huamaní Cárdenas',
                'password' => Hash::make('propietario123'),
                'telefono' => '956789012',
                'rol' => 'propietario',
                'activo' => 1,
                'email_verified_at' => now(),
            ]
        );

        $prop2 = User::firstOrCreate(
            ['email' => 'carmen@gracitas.com'],
            [
                'name' => 'Carmen Rosa Flores',
                'password' => Hash::make('propietario123'),
                'telefono' => '966334455',
                'rol' => 'propietario',
                'activo' => 1,
                'email_verified_at' => now(),
            ]
        );

        $cliente1 = User::firstOrCreate(
            ['email' => 'cliente@gracitas.com'],
            [
                'name' => 'Juan Carlos Pérez',
                'password' => Hash::make('cliente123'),
                'telefono' => '987654321',
                'rol' => 'cliente',
                'activo' => 1,
                'email_verified_at' => now(),
            ]
        );

        $cliente2 = User::firstOrCreate(
            ['email' => 'miguel@gracitas.com'],
            [
                'name' => 'Miguel Ángel Huanca',
                'password' => Hash::make('cliente123'),
                'telefono' => '984112233',
                'rol' => 'cliente',
                'activo' => 1,
            ]
        );

        $cliente3 = User::firstOrCreate(
            ['email' => 'renato@gracitas.com'],
            [
                'name' => 'Renato Gómez Mendoza',
                'password' => Hash::make('cliente123'),
                'telefono' => '956998877',
                'rol' => 'cliente',
                'activo' => 1,
            ]
        );

        // 2. Establecimientos en Coracora, Ayacucho
        $est1 = Establecimiento::firstOrCreate(
            ['nombre' => 'Complejo Deportivo El Golazo Coracora'],
            [
                'propietario_id' => $prop1->id,
                'descripcion' => 'El mejor complejo deportivo de Coracora. Canchas con iluminación LED de alta potencia, césped sintético monofilamento importado, vestuarios con duchas y cafetería.',
                'direccion' => 'Jr. Comercio 340, Coracora',
                'referencia' => 'A media cuadra de la Plaza de Armas de Coracora',
                'telefono' => '956789012',
                'latitud' => -15.0189000,
                'longitud' => -73.7801000,
                'activo' => 1,
            ]
        );

        $est2 = Establecimiento::firstOrCreate(
            ['nombre' => 'Estadio Sintético San Pedro de Coracora'],
            [
                'propietario_id' => $prop2->id,
                'descripcion' => 'Espacio amplio para partidos de fútbol 7 y 8. Gradas techadas para hinchadas, marcador electrónico y zona de parrillas.',
                'direccion' => 'Jr. Arequipa 512, Coracora',
                'referencia' => 'Frente al Mirador Turístico de San Pedro',
                'telefono' => '966334455',
                'latitud' => -15.0165000,
                'longitud' => -73.7780000,
                'activo' => 1,
            ]
        );

        // 3. Canchas (Alberto Huamaní tiene 3 canchas en su único complejo El Golazo)
        $cancha1 = Cancha::firstOrCreate(
            ['nombre' => 'Cancha 1 - Fútbol 7 "Los Campeones"', 'establecimiento_id' => $est1->id],
            [
                'descripcion' => 'Cancha reglamentaria para 7 vs 7, gras de 50mm con caucho granulado, iluminación LED blanca y mallas reforzadas.',
                'capacidad' => 14,
                'precio_hora' => 60.00,
                'duracion_reserva_min' => 60,
                'activa' => 1,
            ]
        );

        $cancha2 = Cancha::firstOrCreate(
            ['nombre' => 'Cancha 2 - Fútbol 5 "La Bombonerita"', 'establecimiento_id' => $est1->id],
            [
                'descripcion' => 'Ideal para partidos rápidos 5 vs 5 o 6 vs 6. Excelente rebote y paredes acolchadas en laterales.',
                'capacidad' => 10,
                'precio_hora' => 45.00,
                'duracion_reserva_min' => 60,
                'activa' => 1,
            ]
        );

        $cancha3 = Cancha::firstOrCreate(
            ['nombre' => 'Cancha 3 - Techada "El Domo"', 'establecimiento_id' => $est1->id],
            [
                'descripcion' => 'Cancha totalmente techada con estructura metálica y lona térmica. Protege de la lluvia y heladas nocturnas.',
                'capacidad' => 12,
                'precio_hora' => 55.00,
                'duracion_reserva_min' => 60,
                'activa' => 1,
            ]
        );

        $cancha4 = Cancha::firstOrCreate(
            ['nombre' => 'Cancha Principal "San Pedro"', 'establecimiento_id' => $est2->id],
            [
                'descripcion' => 'Cancha amplia para fútbol 8 vs 8. Césped con tecnología bicolor antideslumbrante.',
                'capacidad' => 16,
                'precio_hora' => 70.00,
                'duracion_reserva_min' => 60,
                'activa' => 1,
            ]
        );

        // 4. Horarios de Atención (Lunes a Domingo 07:00 a 23:00 para todas las canchas)
        $canchas = [$cancha1, $cancha2, $cancha3, $cancha4];
        foreach ($canchas as $cancha) {
            for ($dia = 1; $dia <= 7; $dia++) {
                HorarioAtencion::firstOrCreate(
                    [
                        'cancha_id' => $cancha->id,
                        'dia_semana' => $dia,
                    ],
                    [
                        'hora_apertura' => '07:00:00',
                        'hora_cierre' => '23:00:00',
                        'activo' => 1,
                    ]
                );
            }
        }

        // 5. Bloqueos de mantenimiento de ejemplo
        $hoy = Carbon::today();
        BloqueoHorario::firstOrCreate(
            [
                'cancha_id' => $cancha1->id,
                'inicio' => $hoy->copy()->addDays(2)->setTime(8, 0, 0),
                'fin' => $hoy->copy()->addDays(2)->setTime(11, 0, 0),
            ],
            [
                'motivo' => 'Mantenimiento preventivo: peinado de césped y reposición de caucho',
                'creado_por' => $prop1->id,
            ]
        );

        // 6. Reservas y Pagos de demostración
        $reserva1 = Reserva::firstOrCreate(
            ['codigo' => 'GC-CORA-001'],
            [
                'cliente_id' => $cliente1->id,
                'cancha_id' => $cancha1->id,
                'inicio' => $hoy->copy()->addDays(1)->setTime(18, 0, 0),
                'fin' => $hoy->copy()->addDays(1)->setTime(19, 0, 0),
                'precio_hora' => 60.00,
                'precio_total' => 60.00,
                'estado' => 'confirmada',
                'observaciones' => 'Reserva en línea para clásico de amigos del barrio',
            ]
        );

        Pago::firstOrCreate(
            ['reserva_id' => $reserva1->id],
            [
                'monto' => 60.00,
                'metodo' => 'efectivo',
                'estado' => 'pendiente',
                'observacion' => 'Pago presencial pendiente al momento de ingresar a la cancha',
            ]
        );

        $reserva2 = Reserva::firstOrCreate(
            ['codigo' => 'GC-CORA-002'],
            [
                'cliente_id' => $cliente2->id,
                'cancha_id' => $cancha2->id,
                'inicio' => $hoy->copy()->subDays(1)->setTime(19, 0, 0),
                'fin' => $hoy->copy()->subDays(1)->setTime(20, 0, 0),
                'precio_hora' => 45.00,
                'precio_total' => 45.00,
                'estado' => 'completada',
                'observaciones' => 'Partido nocturno jugado con normalidad',
            ]
        );

        Pago::firstOrCreate(
            ['reserva_id' => $reserva2->id],
            [
                'monto' => 45.00,
                'metodo' => 'yape',
                'estado' => 'pagado',
                'pagado_en' => $hoy->copy()->subDays(1)->setTime(18, 55, 0),
                'registrado_por' => $prop1->id,
                'observacion' => 'Pagado por Yape en recepción con código de aprobación',
            ]
        );

        $reserva3 = Reserva::firstOrCreate(
            ['codigo' => 'GC-CORA-003'],
            [
                'cliente_id' => $cliente3->id,
                'cancha_id' => $cancha4->id,
                'inicio' => $hoy->copy()->addDays(1)->setTime(20, 0, 0),
                'fin' => $hoy->copy()->addDays(1)->setTime(22, 0, 0),
                'precio_hora' => 70.00,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'observaciones' => 'Reserva manual registrada por llamada telefónica',
            ]
        );

        Pago::firstOrCreate(
            ['reserva_id' => $reserva3->id],
            [
                'monto' => 140.00,
                'metodo' => 'efectivo',
                'estado' => 'pendiente',
                'observacion' => 'Abonará en efectivo antes de iniciar el partido',
            ]
        );
    }
}
