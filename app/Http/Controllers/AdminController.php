<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Verificación manual como en LoginController
        if (session('user_rol') !== 'ADMIN') {
            return redirect()->route('login');
        }

        $usuarios = DB::table('usuarios')->get();

        return view('admin.dashboard', compact('usuarios'));
    }

    public function changeStatus()
    {
        if (session('user_rol') !== 'ADMIN') {
            return redirect()->route('login');
        }

        request()->validate([
            'usuario_id' => 'required|integer',
            'nuevo_estado' => 'required|in:ACTIVO,INACTIVO,PENDIENTE'
        ]);

        DB::table('usuarios')
            ->where('id', request('usuario_id'))
            ->update(['estado' => request('nuevo_estado')]);

        return back()->with('mensaje', 'Estado actualizado correctamente');
    }
}
