<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Busqueda extends Model
{
    protected $table = 'busquedas';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'lugar_salida',
        'lugar_llegada',
        'cantidad_resultados',
        'fecha_busqueda'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
