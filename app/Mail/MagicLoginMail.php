<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MagicLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;

    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function build()
    {
        return $this
            ->subject('Accede a Aventones sin contraseña 🚀')
            ->markdown('emails.magicLogin')
            ->with([
                'user' => $this->user,
                'url'  => $this->url,
            ]);
    }
}
