<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tercero extends Model
{
    use SoftDeletes;

    protected $table = 'terceros';

    protected $fillable = [

        'empresa_id',
        'razon_social',
        'rut',
        'telefono',
        'email',
        'direccion',

        'banco',
        'tipo_cuenta',
        'numero_cuenta',

        'estado'
    ];

     //Relaciones

public function empresa(){

    return $this->belongsTo(Empresa::class);
}

public function documentos(){

    return $this->hasMany(Documento::class);
}

}


