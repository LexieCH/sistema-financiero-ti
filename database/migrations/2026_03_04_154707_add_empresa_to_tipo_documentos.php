<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {

            // crear columna
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');

            // crear foreign key
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('tipo_documentos', function (Blueprint $table) {

            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');

        });
    }
};
