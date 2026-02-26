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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();

            // multiempresa
            $table->foreignId('empresa_id')->constrained('empresas');

            $table->string('nombre');

            $table->decimal('presupuesto', 14, 2)->nullable();

            $table->text('descripcion')->nullable();

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion')->nullable();

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
        Schema::dropIfExists('proyectos');
    }
};
