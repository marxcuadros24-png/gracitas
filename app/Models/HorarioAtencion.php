<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioAtencion extends Model
{
    use HasFactory;

    protected $table = 'horarios_atencion';

    protected $fillable = [
        'cancha_id',
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'dia_semana' => 'integer',
        ];
    }

    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'cancha_id');
    }

    public function getNombreDiaAttribute(): string
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        return $dias[$this->dia_semana] ?? 'Desconocido';
    }
}
