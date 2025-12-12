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

            // Buscar reserva existente
            $reservaExistente = Reserva::where('id_ride', $ride->id)
                ->where('id_pasajero', session('user_id'))
                ->first();

            // Si existe y está activa (PENDIENTE o ACEPTADA) → NO permitir otra
            if ($reservaExistente && in_array($reservaExistente->estado, ['PENDIENTE', 'ACEPTADA'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una reserva activa en este viaje'
                ]);
            }

            // Si existe pero está CANCELADA o RECHAZADA → reutilizar registro
            if ($reservaExistente) {

                // Validar espacios disponibles antes de reactivar
                if ($ride->espacios_disponibles < $request->cantidad_espacios) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay suficientes espacios disponibles'
                    ]);
                }

                // Reactivar reserva
                $reservaExistente->update([
                    'estado'            => 'PENDIENTE',
                    'cantidad_espacios' => $request->cantidad_espacios,
                    'fecha_creacion'    => now(), // fecha de nueva solicitud
                ]);

                // Restar espacios del ride
                $ride->espacios_disponibles -= $request->cantidad_espacios;
                $ride->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Reserva creada nuevamente',
                    'reserva' => $reservaExistente
                ]);
            }

            // ------------------------------
            // Si NO existe ninguna reserva → crear nueva
            // ------------------------------

            if ($ride->espacios_disponibles < $request->cantidad_espacios) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficientes espacios disponibles'
                ]);
            }

            $reserva = Reserva::create([
                'id_ride'            => $ride->id,
                'id_pasajero'        => session('user_id'),
                'cantidad_espacios'  => $request->cantidad_espacios,
                'estado'             => 'PENDIENTE',
                'fecha_creacion'     => now(),
            ]);

            // Reducir espacios disponibles
            $ride->espacios_disponibles -= $request->cantidad_espacios;
            $ride->save();

            return response()->json([
                'success' => true,
                'message' => 'Reserva realizada con éxito',
                'reserva' => $reserva
            ]);

        } catch (\Exception $e) {

            // Modo debug
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
