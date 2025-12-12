<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\MagicLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\MagicLoginMail; // 🔥 IMPORTANTE: Mailable correcto

class MagicLinkController extends Controller
{
    // Enviar link mágico al correo
    public function send(Request $request)
    {
        $request->validate([
            'correo' => 'required|email'
        ]);

        $user = Usuario::where('correo', $request->correo)->first();

        if (!$user) {
            return back()->with('error', 'No existe un usuario con ese correo.');
        }

        if ($user->estado !== 'ACTIVO') {
            return back()->with('error', 'Tu cuenta aún no está activada.');
        }

        // Crear token de login
        $token = Str::random(64);

        MagicLink::create([
            'id_usuario' => $user->id,
            'token'      => $token,
            'expira_en'  => Carbon::now()->addMinutes(10),
            'usado_en'   => null
        ]);

        $url = route('login.magic.access', $token);

        // 🔥 AHORA FUNCIONA: se envía igual que ActivarCuentaMail
        Mail::to($user->correo)->send(new MagicLoginMail($user, $url));

        return back()->with('success', 'Te enviamos un link de acceso a tu correo.');
    }


    // Acceder al sistema con el link mágico
    public function access($token)
    {
        $record = MagicLink::where('token', $token)->first();

        if (!$record)
            return redirect()->route('login')->with('error', 'Enlace inválido.');

        if ($record->usado_en)
            return redirect()->route('login')->with('error', 'Este enlace ya fue usado.');

        if (Carbon::now()->greaterThan($record->expira_en))
            return redirect()->route('login')->with('error', 'Este enlace ha expirado.');

        $user = $record->usuario;

        // Crear sesión
        session([
            'user_id'    => $user->id,
            'user_nombre'=> $user->nombre,
            'user_rol'   => $user->rol,
            'user_foto'  => $user->foto_ruta,
        ]);

        // Marcar como usado
        $record->usado_en = Carbon::now();
        $record->save();

        // Redirigir
        if ($user->rol === 'CHOFER') return redirect()->route('driver.vehicles');
        if ($user->rol === 'ADMIN') return redirect()->route('admin.dashboard');

        return redirect()->route('home');
    }
}
