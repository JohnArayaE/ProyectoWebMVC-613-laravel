<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivarCuentaMail;

class AdminRegistrationController extends Controller
{
    public function create()
    {
        return view('admin.registerAdmin');
    }

    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'nombre'             => 'required|string|max:80',
            'apellido'           => 'required|string|max:80',
            'fecha_nacimiento'   => 'required|date',
            'cedula'             => 'required|string|max:32|unique:usuarios,cedula',
            'correo'             => 'required|email|max:191|unique:usuarios,correo',
            'telefono'           => 'nullable|string|max:32',
            'contrasena'         => 'required|string|min:6',
            'contrasena_repetir' => 'required|same:contrasena',
            'photo'              => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // FOTO
            $fotoRuta = null;

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $file = $request->file('photo');
                $fileName = uniqid() . '_' . $file->getClientOriginalName();

                $uploadPath = public_path('uploads');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $fileName);
                $fotoRuta = 'uploads/' . $fileName;
            }

            // HASH
            $passwordHash = Hash::make($validated['contrasena']);

            // INSERT ADMIN
            $userId = DB::table('usuarios')->insertGetId([
                'nombre'           => $validated['nombre'],
                'apellido'         => $validated['apellido'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'cedula'           => $validated['cedula'],
                'correo'           => $validated['correo'],
                'telefono'         => $validated['telefono'] ?? null,
                'foto_ruta'        => $fotoRuta,
                'contrasena_hash'  => $passwordHash,
                'rol'              => 'ADMIN',
                'estado'           => 'PENDIENTE',
            ]);

            // TOKEN
            $token = Str::random(64);

            DB::table('tokens_activacion')->insert([
                'id_usuario' => $userId,
                'token'      => $token,
                'expira_en'  => now()->addDay(),
            ]);

            DB::commit();

            // CORREO
            Mail::to($validated['correo'])
                ->send(new ActivarCuentaMail($validated['nombre'], $token));

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Administrador creado. Debe activar su cuenta desde el correo.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->with('error', 'Error al registrar administrador.')
                ->withInput();
        }
    }
}
