<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function info($id)
    {
        $ride = Ride::with('vehiculo')->find($id);

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Ride no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ride' => [
                'id' => $ride->id,
                'lugar_salida' => $ride->lugar_salida,
                'lugar_llegada' => $ride->lugar_llegada,
                'dia_semana' => $ride->dia_semana,
                'hora' => $ride->hora,
                'costo' => floatval($ride->costo),
                'espacios_disponibles' => intval($ride->espacios_disponibles),
                'capacidad' => intval($ride->vehiculo->capacidad ?? 0),
                'marca' => $ride->vehiculo->marca ?? '',
                'modelo' => $ride->vehiculo->modelo ?? '',
            ]
        ]);
    }
}
