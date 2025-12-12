<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaPendienteMail;
use Carbon\Carbon;

class NotificarReservasPendientes extends Command
{
    protected $signature = 'reservas:notificar {minutos=30}';
    protected $description = 'Notifica a choferes sobre reservas pendientes por más de X minutos';

    public function handle()
    {
        $minutos = (int)$this->argument('minutos');

        $this->info("Buscando reservas pendientes con más de $minutos minutos...");

        $reservas = DB::table('reservas as r')
            ->join('rides as ride', 'r.id_ride', '=', 'ride.id')
            ->join('usuarios as usr', 'ride.id_chofer', '=', 'usr.id')
            ->select(
                'r.id as reserva_id',
                'r.fecha_creacion',
                'r.cantidad_espacios',
                'ride.nombre_ride',
                'ride.lugar_salida',
                'ride.lugar_llegada',
                'ride.hora',
                'ride.dia_semana',
                'usr.correo as email_chofer',
                'usr.nombre as nombre_chofer',
                DB::raw("TIMESTAMPDIFF(MINUTE, r.fecha_creacion, NOW()) as minutos_pendiente")
            )
            ->where('r.estado', 'PENDIENTE')
            ->having('minutos_pendiente', '>=', $minutos)
            ->get();

        $this->info("Encontradas " . $reservas->count() . " reservas pendientes.");

        foreach ($reservas as $reserva) {
            try {
                Mail::to($reserva->email_chofer)
                    ->send(new ReservaPendienteMail($reserva, $minutos));

                $this->info("Notificación enviada a {$reserva->email_chofer}");
            } catch (\Exception $e) {
                $this->error("Error enviando correo a {$reserva->email_chofer}: " . $e->getMessage());
            }
        }

        $this->info("Proceso terminado.");
    }
}
