<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroCosto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'descripcion',
        'estado'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function movimientos(){
        return $this->hasMany(Movimiento::class);
    }
}