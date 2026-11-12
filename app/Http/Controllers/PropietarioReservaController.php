<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\RealtimeNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PropietarioReservaController extends Controller
{
    public function __construct(
        protected AvailabilityService $availabilityService,
        protected RealtimeNotificationService $realtimeNotificationService,
    ) {}

    public function index(Request $request)
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $canchaIds = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id');

        $query = Reserva::whereIn('cancha_id', $canchaIds)
            ->with(['cliente', 'cancha.establecimiento', 'pago'])
            ->orderBy('inicio', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('cancha_id')) {
            $query->where('cancha_id', $request->cancha_id);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('inicio', $request->fecha);
        }

        $reservas = $query->paginate(15);
        $canchas = Cancha::whereIn('id', $canchaIds)->get();

        return view('propietario.reservas.index', compact('reservas', 'canchas'));
    }

    public function create()
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $canchas = Cancha::whereIn('establecimiento_id', $establecimientoIds)
            ->where('activa', 1)
            ->with('establecimiento')
            ->get();

        if ($canchas->isEmpty()) {
            return redirect()->route('propietario.canchas.create')
                ->with('warning', 'Necesitas tener al menos una cancha activa para registrar reservas.');
        }

        $clientes = User::where('rol', 'cliente')->where('activo', 1)->orderBy('name')->get();

        return view('propietario.reservas.manual', compact('canchas', 'clientes'));
    }

    public function storeManual(Request $request)
    {
        $establecimientoIds = Establecimiento::where('propietario_id', Auth::id())->pluck('id');
        $misCanchas = Cancha::whereIn('establecimiento_id', $establecimientoIds)->pluck('id')->toArray();

        $validated = $request->validate([
            'cancha_id' => 'required|in:'.implode(',', $misCanchas),
            'tipo_cliente' => 'required|in:existente,nuevo',
            'cliente_id' => 'nullable|required_if:tipo_cliente,existente|exists:users,id',
            'nuevo_nombre' => 'nullable|required_if:tipo_cliente,nuevo|string|max:100',
            'nuevo_telefono' => 'nullable|required_if:tipo_cliente,nuevo|string|max:20',
            'nuevo_email' => 'nullable|email|max:150',
            'fecha' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion_min' => 'required|integer|in:60,90,120,180',
            'metodo_pago' => 'required|in:efectivo,yape,plin,otro',
            'estado_pago' => 'required|in:pendiente,pagado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $cancha = Cancha::findOrFail($validated['cancha_id']);
        $inicio = Carbon::parse($validated['fecha'].' '.$validated['hora_inicio'], 'America/Lima');
        $fin = $inicio->copy()->addMinutes((int) $validated['duracion_min']);

        return DB::transaction(function () use ($cancha, $inicio, $fin, $validated) {
            // Validar disponibilidad
            $verificacion = $this->availabilityService->checkAvailability($cancha, $inicio, $fin);

            if (! $verificacion['disponible']) {
                return back()->with('error', $verificacion['mensaje'])->withInput();
            }

            // Resolver cliente
            if ($validated['tipo_cliente'] === 'nuevo') {
                $email = ! empty($validated['nuevo_email'])
                    ? $validated['nuevo_email']
                    : 'cliente_'.Str::slug($validated['nuevo_nombre']).'_'.time().'@gracitas.local';

                $cliente = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $validated['nuevo_nombre'],
                        'telefono' => $validated['nuevo_telefono'] ?? null,
                        'password' => Hash::make(Str::random(12)),
                        'rol' => 'cliente',
                        'activo' => 1,
                    ]
                );
                $clienteId = $cliente->id;
            } else {
                $clienteId = (int) $validated['cliente_id'];
            }

            // Calcular precios
            $horas = $inicio->diffInMinutes($fin) / 60;
            $precioHora = (float) $cancha->precio_hora;
            $precioTotal = round($horas * $precioHora, 2);

            $codigo = 'GC-MAN-'.Carbon::now()->format('ym').'-'.strtoupper(Str::random(4));

            $reserva = Reserva::create([
                'codigo' => $codigo,
                'cliente_id' => $clienteId,
                'cancha_id' => $cancha->id,
                'inicio' => $inicio,
                'fin' => $fin,
                'precio_hora' => $precioHora,
                'precio_total' => $precioTotal,
                'estado' => 'confirmada',
                'observaciones' => '[Reserva Manual Telefónica/Presencial] '.($validated['observaciones'] ?? ''),
            ]);

            Pago::create([
                'reserva_id' => $reserva->id,
                'monto' => $precioTotal,
                'metodo' => $validated['metodo_pago'],
                'estado' => $validated['estado_pago'],
                'pagado_en' => $validated['estado_pago'] === 'pagado' ? Carbon::now('America/Lima') : null,
                'registrado_por' => Auth::id(),
                'observacion' => 'Registrado manualmente en recepción.',
            ]);

            DB::afterCommit(function () use ($reserva, $cancha): void {
                $this->realtimeNotificationService->notificarCambioReserva(
                    $reserva,
                    'manual',
                    "Reserva manual {$reserva->codigo} registrada en {$cancha->nombre}.",
                    "Se registró una reserva en {$cancha->nombre}. Horarios actualizados.",
                );
            });

            return redirect()->route('propietario.reservas.index')
                ->with('success', "Reserva manual registrada exitosamente con el código {$reserva->codigo}. El horario queda bloqueado.");
        });
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        if ($reserva->cancha->establecimiento->propietario_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'estado' => 'required|in:confirmada,completada,no_asistio,cancelada',
            'motivo_cancelacion' => 'nullable|required_if:estado,cancelada|string|max:255',
        ]);

        $reserva->estado = $validated['estado'];

        if ($validated['estado'] === 'cancelada') {
            $reserva->cancelada_en = Carbon::now('America/Lima');
            $reserva->motivo_cancelacion = $validated['motivo_cancelacion'] ?? 'Cancelada por el propietario';
            if ($reserva->pago && $reserva->pago->estado === 'pendiente') {
                $reserva->pago->estado = 'anulado';
                $reserva->pago->save();
            }
        }

        $reserva->save();

        if ($validated['estado'] === 'cancelada') {
            $this->realtimeNotificationService->notificarCambioReserva(
                $reserva,
                'cancelada',
                "Reserva {$reserva->codigo} cancelada desde recepción.",
                'Un horario acaba de quedar disponible nuevamente.',
            );
        }

        return back()->with('success', 'El estado de la reserva ha sido actualizado a '.ucfirst($reserva->estado).'.');
    }
}
