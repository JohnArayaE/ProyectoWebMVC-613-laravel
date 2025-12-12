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

            // Verificar si ya existe una reserva para este ride
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

            // Crear reserva (📌 ahora incluye fecha_creacion)
            $reserva = Reserva::create([
                'id_ride'            => $ride->id,
                'id_pasajero'        => session('user_id'),
                'cantidad_espacios'  => $request->cantidad_espacios,
                'estado'             => 'PENDIENTE',
                'fecha_creacion'     => now(), // ←🔥 Hora actual de tu PC / servidor
            ]);

            // Actualizar espacios disponibles
            $ride->espacios_disponibles -= $request->cantidad_espacios;
            $ride->save();

            return response()->json([
                'success' => true,
                'message' => 'Reserva realizada con éxito',
                'reserva' => $reserva
            ]);

        } catch (\Exception $e) {

            // DEV MODE - Muestra error completo
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
}
