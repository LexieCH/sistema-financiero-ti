<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'rut',
        'nombre',
        'porcentaje_participacion',
        'estado'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function movimientos(){
        return $this->hasMany(Movimiento::class);
    }
}