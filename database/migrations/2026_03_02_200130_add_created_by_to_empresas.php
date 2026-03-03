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
    Schema::table('empresas', function (Blueprint $table) {
        $table->foreignId('creada_por')
            ->nullable()
            ->after('id')
            ->constrained('users')
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('empresas', function (Blueprint $table) {
        $table->dropForeign(['creada_por']);
        $table->dropColumn('creada_por');
    });
}
};