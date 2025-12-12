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

    public $timestamps = false;

    // 🚗 RELACIÓN CON VEHÍCULO
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

    // 👨‍✈️ RELACIÓN CON EL CHOFER
    public function chofer()
    {
        return $this->belongsTo(Usuario::class, 'id_chofer');
    }

    // 📌 RELACIÓN CON RESERVAS (opcional pero recomendado)
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_ride');
    }
}
