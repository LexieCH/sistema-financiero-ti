<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TipoMovimiento;
use App\Models\Categoria;
use App\Models\User;

class Movimiento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'tipo_movimiento_id',
        'categoria_id',
        'metodo_pago_id',
        'tercero_id',
        'monto',
        'fecha',
        'descripcion',
        'estado'
    ];

    public function tipoMovimiento(){
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class,'usuario_id');
    }
}