<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DriverVehicleController extends Controller
{
    /**
     * Mostrar lista de vehículos del chofer
     */
    public function index()
    {
        $userId  = session('user_id');
        $userRol = session('user_rol');

        if (!$userId || $userRol !== 'CHOFER') {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión como chofer.');
        }

        $vehiculos = DB::table('vehiculos')
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->get();

        return view('driver.vehicles', compact('vehiculos'));
    }

    /**
     * Mostrar formulario de crear
     */
    public function create()
    {
        return view('driver.createVehicle');
    }

    /**
     * Guardar nuevo vehículo
     */
    public function store(Request $request)
    {
        $userId = session('user_id');

        // Validaciones
        $request->validate([
            'placa'     => 'required|string|max:20',
            'color'     => 'required|string|max:40',
            'marca'     => 'required|string|max:60',
            'modelo'    => 'required|string|max:60',
            'anio'      => 'required|integer|min:1980|max:' . (date('Y') + 1),
            'capacidad' => 'required|integer|min:1|max:9',
            'foto'      => 'nullable|image|max:5120', // 5MB
        ]);

        // Procesar imagen si se envió
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads/vehicles', 'public');
        }

        DB::table('vehiculos')->insert([
            'id_chofer'     => $userId,
            'placa'         => $request->placa,
            'color'         => $request->color,
            'marca'         => $request->marca,
            'modelo'        => $request->modelo,
            'anio'          => $request->anio,
            'capacidad'     => $request->capacidad,
            'foto_vehiculo' => $fotoPath,
            'estado'        => 'ACTIVO',
        ]);

        return redirect()
            ->route('driver.vehicles')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $userId = session('user_id');

        $vehiculo = DB::table('vehiculos')
            ->where('id', $id)
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('driver.vehicles')
                ->with('error', 'Vehicle not found.');
        }

        return view('driver.editVehicle', compact('vehiculo'));
    }

    /**
     * Actualizar vehículo
     */
    public function update(Request $request, $id)
{
    $userId = session('user_id');

    // Validaciones
    $request->validate([
        'placa'     => 'required|string|max:20',
        'color'     => 'required|string|max:40',
        'marca'     => 'required|string|max:60',
        'modelo'    => 'required|string|max:60',
        'anio'      => 'required|integer|min:1980|max:' . (date('Y') + 1),
        'capacidad' => 'required|integer|min:1|max:9',
        'foto'      => 'nullable|image|max:5120',
    ]);

    // Buscar vehículo válido
    $vehiculo = DB::table('vehiculos')
        ->where('id', $id)
        ->where('id_chofer', $userId)
        ->where('estado', 'ACTIVO')
        ->first();

    if (!$vehiculo) {
        return back()->with('error', 'Vehicle not found.');
    }

    // Mantener foto actual por defecto
    $fotoPath = $vehiculo->foto_vehiculo;

    // SI el usuario sube una nueva foto → reemplazar
    if ($request->hasFile('foto')) {

        // Eliminar foto anterior si existía
        if ($vehiculo->foto_vehiculo && File::exists(storage_path("app/public/{$vehiculo->foto_vehiculo}"))) {
            File::delete(storage_path("app/public/{$vehiculo->foto_vehiculo}"));
        }

        // Guardar nueva foto
        $fotoPath = $request->file('foto')->store('uploads/vehicles', 'public');
    }

    // Actualizar registro
    DB::table('vehiculos')
        ->where('id', $id)
        ->update([
            'placa'         => $request->placa,
            'color'         => $request->color,
            'marca'         => $request->marca,
            'modelo'        => $request->modelo,
            'anio'          => $request->anio,
            'capacidad'     => $request->capacidad,
            'foto_vehiculo' => $fotoPath, // mantiene o reemplaza
        ]);

    return redirect()->route('driver.vehicles')
        ->with('success', 'Vehicle updated successfully.');
}

    /**
     * Eliminación lógica
     */
    public function destroy($id)
    {
        $userId = session('user_id');

        $vehiculo = DB::table('vehiculos')
            ->where('id', $id)
            ->where('id_chofer', $userId)
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return response()->json(['success' => false]);
        }

        DB::table('vehiculos')
            ->where('id', $id)
            ->update(['estado' => 'INACTIVO']);

        return response()->json(['success' => true]);
    }
}
