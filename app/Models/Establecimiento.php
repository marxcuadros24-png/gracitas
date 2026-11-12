<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    use HasFactory;

    protected $table = 'establecimientos';

    protected $fillable = [
        'propietario_id',
        'nombre',
        'descripcion',
        'direccion',
        'referencia',
        'telefono',
        'latitud',
        'longitud',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }

    public function canchas()
    {
        return $this->hasMany(Cancha::class, 'establecimiento_id');
    }

    public function reservas()
    {
        return $this->hasManyThrough(Reserva::class, Cancha::class, 'establecimiento_id', 'cancha_id');
    }
}
