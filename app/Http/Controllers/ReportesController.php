<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Busqueda;

class ReportesController extends Controller
{
    public function index()
{
    return view('admin.reportesBusquedas');
}

public function filtrar(Request $request)
{
    $request->validate([
        'desde' => 'required|date',
        'hasta' => 'required|date'
    ]);

    $busquedas = Busqueda::with('usuario')
        ->whereBetween('fecha_busqueda', [$request->desde, $request->hasta])
        ->get();

    return view('admin.reportesBusquedas', [
        'busquedas' => $busquedas,
        'desde' => $request->desde,
        'hasta' => $request->hasta,
    ]);
}
}
