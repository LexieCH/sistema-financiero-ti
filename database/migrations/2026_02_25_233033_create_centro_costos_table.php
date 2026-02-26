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
        Schema::create('centro_costos', function (Blueprint $table) {
            $table->id();

            // multiempresa
            $table->foreignId('empresa_id')->constrained('empresas');

            // cada centro pertenece a un proyecto
            $table->foreignId('proyecto_id')->constrained('proyectos');

            $table->string('nombre');
            $table->string('descripcion')->nullable();

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
        Schema::dropIfExists('centro_costos');
    }
};
