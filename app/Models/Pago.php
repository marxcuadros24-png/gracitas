<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'reserva_id',
        'monto',
        'metodo',
        'estado',
        'pagado_en',
        'registrado_por',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'pagado_en' => 'datetime',
        ];
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function getEstadoBadgeAttribute(): array
    {
        return match ($this->estado) {
            'pagado' => ['label' => 'Pagado', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'pendiente' => ['label' => 'Pendiente Presencial', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'anulado' => ['label' => 'Anulado', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->estado), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
