<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    public $timestamps = false;  // ← IMPORTANTE

    protected $fillable = [
        'id_ride',
        'id_pasajero',
        'cantidad_espacios',
        'estado'
    ];
}
