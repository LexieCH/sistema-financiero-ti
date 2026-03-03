<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar IDs dinámicamente (NO hardcodear números)
        $ingreso = DB::table('tipo_movimientos')
            ->where('nombre', 'Ingreso')
            ->value('id');

        $egreso = DB::table('tipo_movimientos')
            ->where('nombre', 'Egreso')
            ->value('id');

        $retiro = DB::table('tipo_movimientos')
            ->where('nombre', 'Retiro socio')
            ->value('id');

        $aporte = DB::table('tipo_movimientos')
            ->where('nombre', 'Aporte capital')
            ->value('id');

        DB::table('tipo_documentos')->insert([

            [
                'nombre' => 'Factura Emitida',
                'usa_iva' => 1,
                'credito_fiscal' => 0,
                'categoria' => 'venta',
                'genera_movimiento' => 0, // crédito por defecto
                'tipo_movimiento_id' => null,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Factura Recibida',
                'usa_iva' => 1,
                'credito_fiscal' => 1,
                'categoria' => 'compra',
                'genera_movimiento' => 0,
                'tipo_movimiento_id' => null,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Retiro Socio',
                'usa_iva' => 0,
                'credito_fiscal' => 0,
                'categoria' => 'interno',
                'genera_movimiento' => 1,
                'tipo_movimiento_id' => $retiro,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Aporte Capital',
                'usa_iva' => 0,
                'credito_fiscal' => 0,
                'categoria' => 'interno',
                'genera_movimiento' => 1,
                'tipo_movimiento_id' => $aporte,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}