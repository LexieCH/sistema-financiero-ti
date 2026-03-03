<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'rut_empresa',
        'nombre_fantasia',
        'razon_social',
        'direccion',
        'telefono',
        'email',
        'giro',
        'estado',
        'creada_por'
    ];
}