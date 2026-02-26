<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();

            // empresa (multiempresa)
            $table->foreignId('empresa_id')->constrained('empresas');

            // usuario que registra
            $table->foreignId('usuario_id')->constrained('users');

            // tipo movimiento (ingreso, egreso, retiro socio…)
            $table->foreignId('tipo_movimiento_id')->constrained('tipo_movimientos');

            // categoria financiera
            $table->foreignId('categoria_id')->constrained('categorias');

            // metodo pago
            $table->foreignId('metodo_pago_id')->nullable()->constrained('metodo_pagos');

            // centro costo
            $table->foreignId('centro_costo_id')->nullable()->constrained('centro_costos');

            // socio (solo si es retiro/aporte)
            $table->foreignId('socio_id')->nullable()->constrained('socios');

            // documento asociado (factura, boleta) (solo si existe)
            $table->foreignId('documento_id')->nullable()->constrained('documentos');

            // tercero cliente/proveedor
            $table->foreignId('tercero_id')->nullable()->constrained('terceros');

            $table->dateTime('fecha');

            $table->decimal('monto', 14, 2);

            $table->string('referencia')->nullable();
            // transferencia, comprobante, etc

            $table->text('descripcion')->nullable();

            $table->enum('estado', ['pendiente','confirmado','anulado'])
                ->default('confirmado');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
