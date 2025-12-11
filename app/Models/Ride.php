<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $table = 'rides';
    protected $fillable = [
        'id_vehiculo',
        'id_chofer',
        'lugar_salida',
        'lugar_llegada',
        'dia_semana',
        'hora',
        'costo',
        'espacios_disponibles',
        'estado'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }
}
