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
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('usa_iva');
            $table->boolean('credito_fiscal');
            $table->enum('categoria',['compra','venta','interno']);
            $table->enum('estado',['activo','inactivo'])->default('activo');
            $table->timestamps();
            $table->boolean('genera_movimiento')->default(false);
            $table->foreignId('tipo_movimiento_id')
                ->nullable()
                ->constrained('tipo_movimientos')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
};
