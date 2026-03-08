<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'modulo',
        'accion',
        'registro_id',
        'descripcion'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'usuario_id');
    }

}