<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Permiso;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rols';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    //relaciones

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}