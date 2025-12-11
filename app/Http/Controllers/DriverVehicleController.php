<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DriverVehicleController extends Controller
{
    public function index()
    {
        // Obtener datos desde la sesión manual
        $userId  = session('user_id');
        $userRol = session('user_rol');

        // Si no hay sesión, redirigir al login
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión.');
        }

        // Si NO es chofer, redirigir al login
        if ($userRol !== 'CHOFER') {
            return redirect()->route('login')
                ->with('error', 'Acceso restringido a conductores.');
        }

        // Obtener vehículos del chofer
        $vehiculos = DB::table('vehiculos')
            ->where('id_chofer', $userId)
            ->where('estado', 'activo')
            ->get();

        return view('driver.vehicles', compact('vehiculos'));
    }
}
