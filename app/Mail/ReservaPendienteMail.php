<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaPendienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;
    public $minutos;

    public function __construct($reserva, $minutos)
    {
        $this->reserva = $reserva;
        $this->minutos = $minutos;
    }

    public function build()
    {
        return $this->subject('Recordatorio: Reserva pendiente')
                    ->markdown('emails.reservaPendiente');
    }
}