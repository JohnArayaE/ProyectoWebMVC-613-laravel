<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'id_chofer',
        'marca',
        'modelo',
        'anio',
        'color',
        'placa',
        'capacidad',
        'foto_vehiculo',
        'estado'
    ];

    public function rides()
    {
        return $this->hasMany(Ride::class, 'id_vehiculo');
    }
}
