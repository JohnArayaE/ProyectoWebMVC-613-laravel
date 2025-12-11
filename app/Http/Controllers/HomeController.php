<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $origen  = $request->get('origen');
        $destino = $request->get('destino');
        $orden   = $request->get('orden', 'dia_asc');

        $ridesQuery = Ride::with('vehiculo')
            ->where('estado', 'ACTIVO')
            ->where('espacios_disponibles', '>', 0);

        if ($origen) {
            $ridesQuery->where('lugar_salida', 'LIKE', "%$origen%");
        }

        if ($destino) {
            $ridesQuery->where('lugar_llegada', 'LIKE', "%$destino%");
        }

        // Ordenamiento
        switch ($orden) {
            case 'precio_asc':
                $ridesQuery->orderBy('costo', 'ASC');
                break;

            case 'precio_desc':
                $ridesQuery->orderBy('costo', 'DESC');
                break;

            case 'origen_asc':
                $ridesQuery->orderBy('lugar_salida', 'ASC');
                break;

            case 'origen_desc':
                $ridesQuery->orderBy('lugar_salida', 'DESC');
                break;

            case 'destino_asc':
                $ridesQuery->orderBy('lugar_llegada', 'ASC');
                break;

            case 'destino_desc':
                $ridesQuery->orderBy('lugar_llegada', 'DESC');
                break;

            case 'dia_desc':
                $ridesQuery->orderBy('dia_semana', 'DESC')
                           ->orderBy('hora', 'DESC');
                break;

            default: // dia_asc
                $ridesQuery->orderBy('dia_semana', 'ASC')
                           ->orderBy('hora', 'ASC');
                break;
        }

        $rides = $ridesQuery->get();

        return view('index', compact('rides'));
    }
}
