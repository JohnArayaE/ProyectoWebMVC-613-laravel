<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverRideController extends Controller
{
    /**
     * LISTAR RIDES DEL CHOFER
     */
    public function index()
    {
        $userId = session('user_id');

        $rides = DB::table('rides as r')
            ->join('vehiculos as v', 'r.id_vehiculo', '=', 'v.id')
            ->select(
                'r.*',
                'v.marca',
                'v.modelo',
                'v.placa'
            )
            ->where('r.id_chofer', $userId)
            ->where('r.estado', 'ACTIVO')
            ->orderByRaw("
                CASE r.dia_semana
                    WHEN 'LUNES' THEN 1
                    WHEN 'MARTES' THEN 2
                    WHEN 'MIERCOLES' THEN 3
                    WHEN 'JUEVES' THEN 4
                    WHEN 'VIERNES' THEN 5
                    WHEN 'SABADO' THEN 6
                    WHEN 'DOMINGO' THEN 7
                END
            ")
            ->orderBy('r.hora')
            ->get();

        return view('driver.rides', compact('rides'));
    }

    /**
     * MOSTRAR FORMULARIO DE CREAR RIDE
     */
    public function create()
    {
        $userId = session('user_id');

        $vehiculos = DB::table('vehiculos')
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->get();

        return view('driver.createRide', compact('vehiculos'));
    }

    /**
     * GUARDAR NUEVOS RIDES
     */
    public function store(Request $request)
    {
        $userId = session('user_id');

        $request->validate([
            'nombre_ride'       => 'required|string|max:80',
            'lugar_salida'      => 'required|string|max:120',
            'lugar_llegada'     => 'required|string|max:120',
            'hora'              => 'required',
            'costo'             => 'required|numeric|min:0|max:1000',
            'espacios_totales'  => 'required|integer|min:1|max:9',
            'id_vehiculo'       => 'required|integer',
            'dias_semana'       => 'required|array|min:1'
        ]);

        // Validar vehículo del chofer
        $vehiculo = DB::table('vehiculos')
            ->where('id', $request->id_vehiculo)
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return back()->with('error', 'Invalid vehicle selected.');
        }

        // Validar capacidad
        if ($request->espacios_totales > $vehiculo->capacidad - 1) {
            return back()->with('error', 'Seats exceed vehicle capacity.');
        }

        $created = 0;
        $failed = 0;

        foreach ($request->dias_semana as $dia) {

            // Verificar duplicados
            $exists = DB::table('rides')
                ->where('id_chofer', $userId)
                ->where('id_vehiculo', $request->id_vehiculo)
                ->where('dia_semana', $dia)
                ->where('hora', $request->hora)
                ->where('estado', 'ACTIVO')
                ->exists();

            if ($exists) {
                $failed++;
                continue;
            }

            DB::table('rides')->insert([
                'id_chofer'            => $userId,
                'id_vehiculo'          => $request->id_vehiculo,
                'nombre_ride'          => $request->nombre_ride,
                'lugar_salida'         => $request->lugar_salida,
                'lugar_llegada'        => $request->lugar_llegada,
                'hora'                 => $request->hora,
                'dia_semana'           => $dia,
                'costo'                => $request->costo,
                'espacios_totales'     => $request->espacios_totales,
                'espacios_disponibles' => $request->espacios_totales,
                'estado'               => 'ACTIVO'
            ]);

            $created++;
        }

        // Si no se creó ningún ride → SÍ mostrar error
        if ($created === 0) {
            return back()->with('error', 'No rides were created.');
        }

        // Si se crearon rides → NO mostrar success
        return redirect()->route('driver.rides');
    }

    /**
     * MOSTRAR FORMULARIO DE EDICIÓN
     */
    public function edit($id)
    {
        $userId = session('user_id');

        $ride = DB::table('rides')
            ->where('id', $id)
            ->where('id_chofer', $userId)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.rides')
                ->with('error', 'Ride not found.');
        }

        $vehiculos = DB::table('vehiculos')
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->get();

        return view('driver.editRide', compact('ride', 'vehiculos'));
    }

    /**
     * ACTUALIZAR RIDE
     */
    public function update(Request $request, $id)
    {
        $userId = session('user_id');

        $request->validate([
            'nombre_ride'       => 'required|string|max:80',
            'lugar_salida'      => 'required|string|max:120',
            'lugar_llegada'     => 'required|string|max:120',
            'hora'              => 'required',
            'costo'             => 'required|numeric|min:0|max:1000',
            'espacios_totales'  => 'required|integer|min:1|max:9',
            'id_vehiculo'       => 'required|integer',
            'dia_semana'        => 'required|string',
        ]);

        $ride = DB::table('rides')
            ->where('id', $id)
            ->where('id_chofer', $userId)
            ->first();

        if (!$ride) {
            return redirect()->route('driver.rides')
                ->with('error', 'Ride not found.');
        }

        // Validar vehículo
        $vehiculo = DB::table('vehiculos')
            ->where('id', $request->id_vehiculo)
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return back()->with('error', 'Invalid vehicle selected.');
        }

        // Ajuste de espacios disponibles
        $diff = $request->espacios_totales - $ride->espacios_totales;
        $nuevosDisponibles = $ride->espacios_disponibles + $diff;

        if ($nuevosDisponibles < 0) $nuevosDisponibles = 0;
        if ($nuevosDisponibles > $request->espacios_totales) {
            $nuevosDisponibles = $request->espacios_totales;
        }

        DB::table('rides')
            ->where('id', $id)
            ->update([
                'nombre_ride'         => $request->nombre_ride,
                'lugar_salida'        => $request->lugar_salida,
                'lugar_llegada'       => $request->lugar_llegada,
                'hora'                => $request->hora,
                'dia_semana'          => $request->dia_semana,
                'costo'               => $request->costo,
                'espacios_totales'    => $request->espacios_totales,
                'espacios_disponibles'=> $nuevosDisponibles,
                'id_vehiculo'         => $request->id_vehiculo
            ]);

       
        return redirect()->route('driver.rides');
    }

    /**
     * ELIMINAR RIDE (LÓGICO)
     */
    public function destroy($id)
    {
        $userId = session('user_id');

        DB::table('rides')
            ->where('id', $id)
            ->where('id_chofer', $userId)
            ->update(['estado' => 'CANCELADO']);

        // NO success message
        return redirect()->route('driver.rides');
    }
}
