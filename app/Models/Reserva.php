<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'codigo',
        'cliente_id',
        'cancha_id',
        'inicio',
        'fin',
        'precio_hora',
        'precio_total',
        'estado',
        'observaciones',
        'cancelada_en',
        'motivo_cancelacion',
    ];

    protected function casts(): array
    {
        return [
            'inicio' => 'datetime',
            'fin' => 'datetime',
            'cancelada_en' => 'datetime',
            'precio_hora' => 'decimal:2',
            'precio_total' => 'decimal:2',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'cancha_id');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'reserva_id');
    }

    public function puedeCancelarPorCliente(): bool
    {
        if ($this->estado !== 'confirmada') {
            return false;
        }

        // Permitir cancelación si faltan al menos 2 horas para el inicio
        return Carbon::now()->diffInMinutes($this->inicio, false) >= 120;
    }

    public function getEstadoBadgeAttribute(): array
    {
        return match ($this->estado) {
            'confirmada' => ['label' => 'Confirmada', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'completada' => ['label' => 'Completada', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'cancelada' => ['label' => 'Cancelada', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
            'no_asistio' => ['label' => 'No Asistió', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
            default => ['label' => ucfirst($this->estado), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
