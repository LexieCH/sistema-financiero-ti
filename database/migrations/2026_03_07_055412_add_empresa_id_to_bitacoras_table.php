<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bitacoras')) {
            return;
        }

        if (Schema::hasColumn('bitacoras', 'empresa_id')) {
            return;
        }

        Schema::table('bitacoras', function (Blueprint $table) {

            $table->foreignId('empresa_id')
                ->after('id')
                ->constrained('empresas');

        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('bitacoras')) {
            return;
        }

        if (!Schema::hasColumn('bitacoras', 'empresa_id')) {
            return;
        }

        Schema::table('bitacoras', function (Blueprint $table) {

            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');

        });
    }
};