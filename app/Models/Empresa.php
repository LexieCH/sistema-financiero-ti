<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'rut_empresa',
        'nombre_fantasia',
        'razon_social',
        'direccion',
        'telefono',
        'email',
        'estado'
    ];
}