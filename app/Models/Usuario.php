<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios'; // tu tabla real

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'correo',
        'telefono',
        'foto_ruta',
        'contrasena_hash',
        'rol',
        'estado',
    ];

    public $timestamps = false; 

    // Relaciones
    public function ridesComoChofer()
    {
        return $this->hasMany(Ride::class, 'id_chofer');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_pasajero');
    }
}