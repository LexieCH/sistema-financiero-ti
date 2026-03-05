<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CentroCosto extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $table = 'centro_costos';
    
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