<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'presupuesto',
        'descripcion',
        'fecha_inicio',
        'fecha_finalizacion',
        'estado'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function centrosCostos()
    {
        return $this->hasMany(CentroCosto::class);
    }
}