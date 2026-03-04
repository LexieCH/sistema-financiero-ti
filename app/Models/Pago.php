<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'empresa_id',
        'documento_id',
        'usuario_id',
        'metodo_pago_id',
        'fecha_pago',
        'monto',
        'observacion',
        'timestamps'
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }
    }