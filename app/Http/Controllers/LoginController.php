<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Mostrar formulario
    public function show()
    {
        return view('login');
    }

    // Procesar inicio de sesión
    public function login(Request $request)
    {
        // Validar entradas
        $credentials = $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required|string|min:6',
        ]);

        // Buscar usuario por correo
        $usuario = DB::table('usuarios')
            ->where('correo', $credentials['correo'])
            ->first();

        if (!$usuario) {
            return back()->with('error', 'Correo o contraseña incorrectos.');
        }

        // Verificar contraseña hash
        if (!Hash::check($credentials['contrasena'], $usuario->contrasena_hash)) {
            return back()->with('error', 'Correo o contraseña incorrectos.');
        }

        // Verificar si está activo
        if ($usuario->estado !== 'ACTIVO') {
            return back()->with('error', 'Debe activar su cuenta antes de ingresar.');
        }

        // Crear sesión manual (NO estamos usando Auth estándar)
        session([
            'user_id'    => $usuario->id,
            'user_nombre'=> $usuario->nombre,
            'user_rol'   => $usuario->rol,
            'user_foto'  => $usuario->foto_ruta,
        ]);

        // Redirigir según rol
        if ($usuario->rol === 'CHOFER') {
            return redirect()->route('driver.vehicles');
        }

        if ($usuario->rol === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        }

        // USUARIO normal
        return redirect()->route('home');
    }

    // Cerrar sesión
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
