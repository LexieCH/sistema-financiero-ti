<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')->constrained('empresas');

            $table->foreignId('documento_id')
                ->constrained('documentos')
                ->onDelete('cascade');

            $table->foreignId('usuario_id')->constrained('users');

            $table->foreignId('metodo_pago_id')
                ->nullable()
                ->constrained('metodo_pagos')
                ->nullOnDelete();

            $table->date('fecha_pago');

            $table->decimal('monto', 14, 2);

            $table->text('observacion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};