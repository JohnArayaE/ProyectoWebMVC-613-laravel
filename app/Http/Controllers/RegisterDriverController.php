<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivarCuentaMail;

class RegisterDriverController extends Controller

{
    public function create()
    {
        return view('registerDriver');
    }

    public function store(Request $request)
    {
        // 1) Validar datos del formulario
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
            // 2) Manejo de foto
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

            // 3) Hash de contraseña
            $passwordHash = Hash::make($validated['contrasena']);

            // 4) Insertar usuario
            $userId = DB::table('usuarios')->insertGetId([
                'nombre'           => $validated['nombre'],
                'apellido'         => $validated['apellido'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'cedula'           => $validated['cedula'],
                'correo'           => $validated['correo'],
                'telefono'         => $validated['telefono'] ?? null,
                'foto_ruta'        => $fotoRuta,
                'contrasena_hash'  => $passwordHash,
                'rol'              => 'CHOFER',   // ← DIFERENCIA: SIEMPRE CHOFER
                'estado'           => 'PENDIENTE',
            ]);

            // 5) Crear token activación
            $token = Str::random(64);
            $expiraEn = now()->addDay();

            DB::table('tokens_activacion')->insert([
                'id_usuario' => $userId,
                'token'      => $token,
                'expira_en'  => $expiraEn,
            ]);

            DB::commit();

            // 6) Enviar correo
            Mail::to($validated['correo'])
                ->send(new ActivarCuentaMail($validated['nombre'], $token));

            return redirect()
                ->route('login')
                ->with('success', 'Registro de conductor exitoso. Revisa tu correo para activar tu cuenta.');

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return back()
                ->with('error', 'Ocurrió un error al registrarte como conductor. Intenta nuevamente.')
                ->withInput();
        }
    }

    public function activate(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()
                ->route('login')
                ->with('error', 'No se proporcionó token.');
        }

        $row = DB::table('tokens_activacion as ta')
            ->join('usuarios as u', 'ta.id_usuario', '=', 'u.id')
            ->where('ta.token', $token)
            ->whereNull('ta.usado_en')
            ->where('ta.expira_en', '>', now())
            ->select('ta.id as token_id', 'u.id as usuario_id')
            ->first();

        if (!$row) {
            return redirect()
                ->route('login')
                ->with('error', 'Token inválido o expirado.');
        }

        DB::beginTransaction();

        try {
            DB::table('usuarios')
                ->where('id', $row->usuario_id)
                ->update(['estado' => 'ACTIVO']);

            DB::table('tokens_activacion')
                ->where('id', $row->token_id)
                ->update(['usado_en' => now()]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Cuenta de conductor activada exitosamente.');

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return redirect()
                ->route('login')
                ->with('error', 'Error al activar la cuenta.');
        }
    }
}
