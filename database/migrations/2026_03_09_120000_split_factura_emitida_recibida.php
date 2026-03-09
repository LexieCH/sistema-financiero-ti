<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $ingresoId = DB::table('tipo_movimientos')
            ->where(function ($query) {
                $query->where('naturaleza', 'ingreso')
                    ->orWhereRaw("LOWER(nombre) = 'ingreso'");
            })
            ->orderBy('id')
            ->value('id');

        $egresoId = DB::table('tipo_movimientos')
            ->where(function ($query) {
                $query->where('naturaleza', 'egreso')
                    ->orWhereRaw("LOWER(nombre) = 'egreso'");
            })
            ->orderBy('id')
            ->value('id');

        $facturas = DB::table('tipo_documentos')
            ->whereRaw("LOWER(nombre) = 'factura'")
            ->get();

        foreach ($facturas as $factura) {
            $empresaId = $factura->empresa_id;

            $emitidaExiste = DB::table('tipo_documentos')
                ->where('nombre', 'Factura Emitida')
                ->when(is_null($empresaId), function ($query) {
                    $query->whereNull('empresa_id');
                }, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->exists();

            if (!$emitidaExiste) {
                DB::table('tipo_documentos')
                    ->where('id', $factura->id)
                    ->update([
                        'nombre' => 'Factura Emitida',
                        'categoria' => 'venta',
                        'usa_iva' => 1,
                        'credito_fiscal' => 0,
                        'tipo_movimiento_id' => $ingresoId,
                        'updated_at' => now(),
                    ]);
            }

            $recibidaExiste = DB::table('tipo_documentos')
                ->where('nombre', 'Factura Recibida')
                ->when(is_null($empresaId), function ($query) {
                    $query->whereNull('empresa_id');
                }, function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->exists();

            if (!$recibidaExiste) {
                DB::table('tipo_documentos')->insert([
                    'empresa_id' => $empresaId,
                    'nombre' => 'Factura Recibida',
                    'usa_iva' => 1,
                    'credito_fiscal' => 1,
                    'categoria' => 'compra',
                    'estado' => 'activo',
                    'genera_movimiento' => 0,
                    'tipo_movimiento_id' => $egresoId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $hayEmitidaGlobal = DB::table('tipo_documentos')
            ->whereNull('empresa_id')
            ->where('nombre', 'Factura Emitida')
            ->exists();

        $hayRecibidaGlobal = DB::table('tipo_documentos')
            ->whereNull('empresa_id')
            ->where('nombre', 'Factura Recibida')
            ->exists();

        if (!$hayEmitidaGlobal) {
            DB::table('tipo_documentos')->insert([
                'empresa_id' => null,
                'nombre' => 'Factura Emitida',
                'usa_iva' => 1,
                'credito_fiscal' => 0,
                'categoria' => 'venta',
                'estado' => 'activo',
                'genera_movimiento' => 0,
                'tipo_movimiento_id' => $ingresoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!$hayRecibidaGlobal) {
            DB::table('tipo_documentos')->insert([
                'empresa_id' => null,
                'nombre' => 'Factura Recibida',
                'usa_iva' => 1,
                'credito_fiscal' => 1,
                'categoria' => 'compra',
                'estado' => 'activo',
                'genera_movimiento' => 0,
                'tipo_movimiento_id' => $egresoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // No revertimos datos de negocio para evitar perder configuraciones creadas por usuarios.
    }
};
