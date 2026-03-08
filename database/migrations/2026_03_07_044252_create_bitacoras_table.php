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
        Schema::create('bitacoras', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empresa_id')->constrained('empresas');

            $table->foreignId('usuario_id')->constrained('users');

            $table->string('modulo'); 
            // ejemplo: Documento, Movimiento, Tercero

            $table->string('accion'); 
            // crear, editar, eliminar

            $table->unsignedBigInteger('registro_id')->nullable();
            // id del registro afectado

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

};