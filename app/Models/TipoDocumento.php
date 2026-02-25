<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    public function run(): void
    {
        TipoDocumento::create([
            'nombre'=>'Factura Emitida',
            'usa_iva'=>true,
            'credito_fiscal'=>false,
            'categoria'=>'venta'   
        ]);

         TipoDocumento::create([
            'nombre'=>'Factura Recibida',
            'usa_iva'=>true,
            'credito_fiscal'=>true,
            'categoria'=>'compra'   
        ]);

         TipoDocumento::create([
            'nombre'=>'Nota de Crédito',
            'usa_iva'=>true,
            'credito_fiscal'=>true,
            'categoria'=>'compra'   
        ]);
            
        
         TipoDocumento::create([
            'nombre'=>'Nota de débito',
            'usa_iva'=>false,
            'credito_fiscal'=>false,
            'categoria'=>'interno'   
        
        ]);
    }
}
