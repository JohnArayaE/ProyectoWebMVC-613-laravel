<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'id_ride',
        'id_pasajero',
        'estado',
        'cantidad_espacios'
    ];

    public $timestamps = false;

    // 🔥 RELACIÓN CON EL RIDE
    public function ride()
    {
        return $this->belongsTo(Ride::class, 'id_ride');
    }

    // 🔥 RELACIÓN CON EL PASAJERO
    public function pasajero()
    {
        return $this->belongsTo(Usuario::class, 'id_pasajero');
    }
}
