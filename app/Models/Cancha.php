<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    protected $table = 'canchas';

    protected $fillable = [
        'establecimiento_id',
        'nombre',
        'descripcion',
        'capacidad',
        'precio_hora',
        'duracion_reserva_min',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
            'precio_hora' => 'decimal:2',
            'capacidad' => 'integer',
            'duracion_reserva_min' => 'integer',
        ];
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function horariosAtencion()
    {
        return $this->hasMany(HorarioAtencion::class, 'cancha_id');
    }

    public function bloqueos()
    {
        return $this->hasMany(BloqueoHorario::class, 'cancha_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'cancha_id');
    }
}
