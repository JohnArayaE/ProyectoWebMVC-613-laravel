<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Reserva;
use Illuminate\Http\Request;

class RideReservationController extends Controller
{
    public function reservar(Request $request)
    {
        if (!session('user_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $request->validate([
            'ride_id' => 'required|integer',
            'cantidad_espacios' => 'required|integer|min:1'
        ]);

        $ride = Ride::find($request->ride_id);

        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'El ride no existe'
            ], 404);
        }

        if ($ride->espacios_disponibles < $request->cantidad_espacios) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficientes espacios disponibles'
            ]);
        }

        // Registrar reserva
        $reserva = Reserva::create([
            'id_ride' => $ride->id,
            'id_pasajero' => session('user_id'),
            'cantidad_espacios' => $request->cantidad_espacios,
            'estado' => 'PENDIENTE'
        ]);

        // Actualizar cupos
        $ride->espacios_disponibles -= $request->cantidad_espacios;
        $ride->save();

        return response()->json([
            'success' => true,
            'message' => 'Reserva realizada con éxito',
            'reserva' => $reserva
        ]);
    }
}
