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
        Schema::create('tipo_movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('naturaleza', ['ingreso','egreso','interno']);
            $table->boolean('afecta_caja')->default(true);
            $table->boolean('requiere_socio')->default(false);
            $table->boolean('estado')->default(true);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_movimientos');
    }
};
