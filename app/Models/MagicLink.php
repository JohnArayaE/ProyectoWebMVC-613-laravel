<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagicLink extends Model
{
    protected $table = 'magic_links';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'token',
        'expira_en',
        'usado_en'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}