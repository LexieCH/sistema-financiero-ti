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
        'tipo',          // cliente, proveedor, ambos
        'rut',
        'nombre',
        'telefono',
        'email',
        'direccion',
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


