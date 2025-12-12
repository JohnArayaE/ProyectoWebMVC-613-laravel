<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Ride;
use App\Models\Usuario;

class BookingsController extends Controller
{
    public function index()
    {
        // USUARIO POR SESIÓN (tu sistema funciona así)
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = Usuario::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        // SI ES CHOFER → ver reservas de sus rides
        if ($user->rol === 'CHOFER') {
            $bookings = Reserva::with(['ride.vehiculo', 'pasajero'])
                ->whereHas('ride', function ($q) use ($user) {
                    $q->where('id_chofer', $user->id);
                })
                ->orderBy('id', 'DESC')
                ->get();
        
        // SI ES PASAJERO → ver reservas hechas por él
        } else {
            $bookings = Reserva::with(['ride.vehiculo', 'ride.chofer'])
                ->where('id_pasajero', $user->id)
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('bookings', compact('bookings', 'user'));
    }


    public function updateBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'action' => 'required|string'
        ]);

        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user = Usuario::find($userId);

        $booking = Reserva::with('ride')->find($request->booking_id);
        if (!$booking) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        }

        // VALIDACIÓN DE PERMISOS
        if ($request->action !== 'cancel') {
            // Solo el chofer puede aceptar o rechazar
            if ($booking->ride->id_chofer !== $user->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        } else {
            // Cancelar → pasajero o chofer
            if ($booking->id_pasajero !== $user->id &&
                $booking->ride->id_chofer !== $user->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }

        // CAMBIOS DE ESTADO
        switch ($request->action) {

            case 'accept':
                $booking->estado = 'ACEPTADA';
                break;

            case 'reject':
                $booking->estado = 'RECHAZADA';
                $booking->ride->espacios_disponibles += $booking->cantidad_espacios;
                $booking->ride->save();
                break;

            case 'cancel':
                $booking->estado = 'CANCELADA';
                $booking->ride->espacios_disponibles += $booking->cantidad_espacios;
                $booking->ride->save();
                break;
        }

        $booking->save();

        return response()->json(['success' => true]);
    }
}