<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipo_documentos';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'usa_iva',
        'credito_fiscal',
        'categoria',
        'estado',
        'genera_movimiento',
        'tipo_movimiento_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
