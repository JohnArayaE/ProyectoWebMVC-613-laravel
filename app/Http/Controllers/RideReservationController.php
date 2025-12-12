<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Reserva;
use Illuminate\Http\Request;

class RideReservationController extends Controller
{
    public function reservar(Request $request)
    {
        try {

            // Validar sesión
            if (!session('user_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validación del request
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

            // Validar si YA existe reserva de este pasajero para este ride
            $reservaExistente = Reserva::where('id_ride', $ride->id)
                ->where('id_pasajero', session('user_id'))
                ->first();

            if ($reservaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una reserva en este viaje'
                ]);
            }

            // Validar espacios
            if ($ride->espacios_disponibles < $request->cantidad_espacios) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficientes espacios disponibles'
                ]);
            }

            // Crear reserva
            $reserva = Reserva::create([
                'id_ride' => $ride->id,
                'id_pasajero' => session('user_id'),
                'cantidad_espacios' => $request->cantidad_espacios,
                'estado' => 'PENDIENTE'
            ]);

            // Actualizar los espacios disponibles
            $ride->espacios_disponibles -= $request->cantidad_espacios;
            $ride->save();

            return response()->json([
                'success' => true,
                'message' => 'Reserva realizada con éxito',
                'reserva' => $reserva
            ]);

        } catch (\Exception $e) {

            // ⚠️ DEBUG COMPLETO – PARA VER EL ERROR REAL
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage(),   // mensaje real del error
                'line' => $e->getLine(),       // línea exacta donde explotó
                'file' => $e->getFile(),       // archivo exacto
            ], 500);
        }
    }
}
