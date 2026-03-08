<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            if (Schema::hasColumn('documentos', 'tercero_id')) {
                $table->dropConstrainedForeignId('tercero_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            if (!Schema::hasColumn('documentos', 'tercero_id')) {
                $table->foreignId('tercero_id')
                    ->nullable()
                    ->after('empresa_id')
                    ->constrained('terceros')
                    ->nullOnDelete();
            }
        });
    }
};
