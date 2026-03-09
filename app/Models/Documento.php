<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TipoDocumento;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Movimiento;
use App\Models\Pago;
use App\Models\Tercero;

class Documento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'tercero_id',
        'usuario_id',
        'tipo_documento_id',
        'folio',
        'fecha_emision',
        'fecha_vencimiento',
        'monto_neto',
        'iva',
        'total',
        'estado',
        'pdf_url'
    ];

    // Relaciones

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function totalPagado()
    {
        return $this->pagos()->sum('monto');
    }

    public function saldoPendiente()
    {
        return $this->total - $this->totalPagado();
    }

}

