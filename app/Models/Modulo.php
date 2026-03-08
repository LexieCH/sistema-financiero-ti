<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}
