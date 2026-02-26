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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();

            // multiempresa
            $table->foreignId('empresa_id')->constrained('empresas');

            // nombre categoria
            $table->string('nombre');

            // si es ingreso o egreso
            $table->enum('tipo', ['ingreso','egreso']);

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
        Schema::dropIfExists('categorias');
    }
};
