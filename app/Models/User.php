<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Identificador clave para el token JWT.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Claims personalizados integrados dentro del payload JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->rol,
            'activo' => (bool) $this->activo,
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isPropietario(): bool
    {
        return $this->rol === 'propietario';
    }

    public function isCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    /**
     * Un propietario tiene un único establecimiento (local).
     */
    public function establecimiento(): HasOne
    {
        return $this->hasOne(Establecimiento::class, 'propietario_id');
    }

    /**
     * Compatibilidad con colecciones previas.
     */
    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class, 'propietario_id');
    }

    /**
     * Un propietario puede tener muchas canchas a través de su único local.
     */
    public function canchas(): HasManyThrough
    {
        return $this->hasManyThrough(Cancha::class, Establecimiento::class, 'propietario_id', 'establecimiento_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'cliente_id');
    }

    public function bloqueosCreados()
    {
        return $this->hasMany(BloqueoHorario::class, 'creado_por');
    }

    public function pagosRegistrados()
    {
        return $this->hasMany(Pago::class, 'registrado_por');
    }

    /**
     * Verifica si el usuario tiene reservas activas programadas a futuro o en curso.
     */
    public function hasActiveReservations(): bool
    {
        $now = Carbon::now('America/Lima');

        if ($this->isCliente()) {
            return $this->reservas()
                ->where('estado', 'confirmada')
                ->where('fin', '>=', $now)
                ->exists();
        }

        if ($this->isPropietario()) {
            $canchaIds = $this->establecimiento ? $this->establecimiento->canchas()->pluck('id') : collect();

            return Reserva::whereIn('cancha_id', $canchaIds)
                ->where('estado', 'confirmada')
                ->where('fin', '>=', $now)
                ->exists();
        }

        return false;
    }

    /**
     * Elimina el usuario y todas sus relaciones en cascada de manera segura y transaccional.
     */
    public function deleteWithRelations(): void
    {
        DB::transaction(function () {
            // 1. Desvincular cualquier pago que este usuario haya registrado como cobrador/propietario
            Pago::where('registrado_por', $this->id)->update(['registrado_por' => null]);

            // 2. Si es cliente: anonimizar datos personales si tiene reservas/pagos históricos para preservar
            // la integridad financiera y contable de los establecimientos deportivos
            if ($this->isCliente()) {
                if ($this->reservas()->exists()) {
                    $this->update([
                        'name' => 'Usuario Eliminado',
                        'email' => 'eliminado_'.$this->id.'_'.time().'@gracitas.local',
                        'telefono' => null,
                        'password' => Hash::make(Str::random(32)),
                        'activo' => false,
                        'remember_token' => null,
                    ]);

                    return;
                }
            }

            // 3. Si es propietario: eliminar pagos, reservas, horarios, bloqueos, canchas y su único local
            if ($this->isPropietario()) {
                $establecimiento = $this->establecimiento;
                if ($establecimiento) {
                    $canchaIds = $establecimiento->canchas()->pluck('id');

                    // Pagos de las reservas en sus canchas
                    $reservaIds = Reserva::whereIn('cancha_id', $canchaIds)->pluck('id');
                    Pago::whereIn('reserva_id', $reservaIds)->delete();

                    // Reservas en sus canchas
                    Reserva::whereIn('cancha_id', $canchaIds)->delete();

                    // Horarios y bloqueos
                    HorarioAtencion::whereIn('cancha_id', $canchaIds)->delete();
                    BloqueoHorario::whereIn('cancha_id', $canchaIds)->delete();

                    // Canchas
                    $establecimiento->canchas()->delete();

                    // Local deportivo
                    $establecimiento->delete();
                }
            }

            // 4. Bloqueos manuales creados por este usuario
            BloqueoHorario::where('creado_por', $this->id)->delete();

            // 5. Eliminar la cuenta del usuario
            $this->delete();
        });
    }

    /**
     * Enviar notificación de restablecimiento de contraseña personalizada en español.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Enviar notificación de verificación de correo electrónico personalizada en español.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }
}
