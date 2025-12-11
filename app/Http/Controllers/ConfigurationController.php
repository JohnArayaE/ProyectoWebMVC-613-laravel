<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function show()
    {
        // Verificar login
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $userId = session('user_id');

        // Obtener datos del usuario
        $usuario = DB::table('usuarios')->where('id', $userId)->first();

        return view('configurations', compact('usuario'));
    }


    public function update(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $userId = session('user_id');

        // VALIDACIÓN
        $validated = $request->validate([
            'nombre'  => 'required|string|max:80',
            'apellido'=> 'required|string|max:80',
            'cedula'  => 'required|string|max:32|unique:usuarios,cedula,'.$userId,
            'correo'  => 'required|email|max:191|unique:usuarios,correo,'.$userId,
            'telefono'=> 'nullable|string|max:32',
            'fecha_nacimiento' => 'required|date',
            'foto'    => 'nullable|image|max:5120',
        ]);

        // PROCESAR FOTO NUEVA SI EXISTE
        $fotoPath = null;

        if ($request->hasFile('foto')) {

            $folder = "uploads/{$userId}/";

            if (!is_dir(public_path($folder))) {
                mkdir(public_path($folder), 0777, true);
            }

            $file = $request->file('foto');
            $newName = uniqid().'_'.$file->getClientOriginalName();

            $file->move(public_path($folder), $newName);

            $fotoPath = $folder.$newName;

            // borrar foto anterior
            $old = DB::table('usuarios')->where('id', $userId)->value('foto_ruta');

            if ($old && file_exists(public_path($old))) {
                unlink(public_path($old));
            }
        }

        // ACTUALIZAR
        DB::table('usuarios')->where('id', $userId)->update([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'cedula' => $validated['cedula'],
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'foto_ruta' => $fotoPath ? $fotoPath : DB::raw('foto_ruta'),
        ]);

        // actualizar sesión
        session([
            'user_nombre' => $validated['nombre'],
            'user_foto'   => $fotoPath ? $fotoPath : session('user_foto')
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
