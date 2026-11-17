<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Pago;
use App\Models\Reserva;
use App\Services\AvailabilityService;
use App\Services\RealtimeNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservaClienteController extends Controller
{
    public function __construct(
        protected AvailabilityService $availabilityService,
        protected RealtimeNotificationService $realtimeNotificationService,
    ) {}

    public function misReservas(Request $request)
    {
        $clienteId = Auth::id();
        $query = Reserva::where('cliente_id', $clienteId)
            ->with(['cancha.establecimiento', 'pago'])
            ->orderBy('inicio', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->paginate(10);

        return view('cliente.reservas', compact('reservas'));
    }

    public function create(Request $request, Cancha $cancha)
    {
        $fecha = $request->query('fecha', Carbon::tomorrow('America/Lima')->format('Y-m-d'));
        $hora = $request->query('hora', '19:00');
        $duracion = (int) $request->query('duracion', 60);

        return view('cliente.reservar_paso2', compact('cancha', 'fecha', 'hora', 'duracion'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date_format:Y-m-d',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion_min' => 'required|integer|in:60,120,180',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'duracion_min.required' => 'La duración es obligatoria.',
        ]);

        // Usamos transacción de base de datos con bloqueo pesimista para evitar colisiones simultáneas
        return DB::transaction(function () use ($validated) {
            $cancha = Cancha::where('id', $validated['cancha_id'])->lockForUpdate()->firstOrFail();
            $inicio = Carbon::parse($validated['fecha'].' '.$validated['hora_inicio'], 'America/Lima');
            $fin = $inicio->copy()->addMinutes((int) $validated['duracion_min']);

            // Validar disponibilidad
            $verificacion = $this->availabilityService->checkAvailability($cancha, $inicio, $fin);

            if (! $verificacion['disponible']) {
                return back()->with('error', $verificacion['mensaje'])->withInput();
            }

            // Calcular precios
            $horas = $inicio->diffInMinutes($fin) / 60;
            $precioHora = (float) $cancha->precio_hora;
            $precioTotal = round($horas * $precioHora, 2);

            // Generar código único: GC-AñoMes-Random
            $codigo = 'GC-'.Carbon::now()->format('ym').'-'.strtoupper(Str::random(5));

            $reserva = Reserva::create([
                'codigo' => $codigo,
                'cliente_id' => Auth::id(),
                'cancha_id' => $cancha->id,
                'inicio' => $inicio,
                'fin' => $fin,
                'precio_hora' => $precioHora,
                'precio_total' => $precioTotal,
                'estado' => 'confirmada',
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Registrar pago presencial en estado pendiente
            Pago::create([
                'reserva_id' => $reserva->id,
                'monto' => $precioTotal,
                'metodo' => 'efectivo',
                'estado' => 'pendiente',
                'observacion' => 'Pago presencial a realizarse en el establecimiento antes de ingresar al campo.',
            ]);

            DB::afterCommit(function () use ($reserva, $cancha): void {
                $this->realtimeNotificationService->notificarCambioReserva(
                    $reserva,
                    'creada',
                    "Nueva reserva {$reserva->codigo} en {$cancha->nombre}.",
                    "Se registró una reserva en {$cancha->nombre}. Horarios actualizados.",
                );
            });

            return redirect()->route('cliente.reserva.comprobante', $reserva->codigo)
                ->with('success', '¡Tu reserva ha sido confirmada con éxito! Revisa tu comprobante a continuación.');
        });
    }

    public function comprobante($codigo)
    {
        $reserva = Reserva::where('codigo', $codigo)
            ->with(['cancha.establecimiento.propietario', 'cliente', 'pago'])
            ->firstOrFail();

        // Validar que pertenezca al usuario cliente, al propietario del establecimiento o a un administrador
        $user = Auth::user();
        if ($user->isCliente() && (int) $reserva->cliente_id !== (int) $user->id) {
            abort(403, 'No tienes permiso para ver esta reserva.');
        }

        if ($user->isPropietario()) {
            $propietarioId = (int) ($reserva->cancha?->establecimiento?->propietario_id ?? 0);
            if ($propietarioId !== (int) $user->id) {
                abort(403, 'No tienes permiso para ver comprobantes de otros establecimientos deportivos.');
            }
        }

        return view('cliente.comprobante', compact('reserva'));
    }

    public function cancelar(Request $request, Reserva $reserva)
    {
        $user = Auth::user();

        if ($reserva->cliente_id !== $user->id) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        if (! $reserva->puedeCancelarPorCliente()) {
            return back()->with('error', 'Solo es posible cancelar reservas confirmadas con al menos 2 horas de anticipación a la hora pactada.');
        }

        $validated = $request->validate([
            'motivo_cancelacion' => 'required|string|max:255',
        ], [
            'motivo_cancelacion.required' => 'Debes indicar el motivo de la cancelación.',
        ]);

        $reserva->estado = 'cancelada';
        $reserva->cancelada_en = Carbon::now('America/Lima');
        $reserva->motivo_cancelacion = $validated['motivo_cancelacion'];
        $reserva->save();

        if ($reserva->pago && $reserva->pago->estado === 'pendiente') {
            $reserva->pago->estado = 'anulado';
            $reserva->pago->save();
        }

        $this->realtimeNotificationService->notificarCambioReserva(
            $reserva,
            'cancelada',
            "El cliente canceló la reserva {$reserva->codigo}.",
            'Un horario acaba de quedar disponible nuevamente.',
        );

        return back()->with('success', 'La reserva fue cancelada correctamente.');
    }
}
