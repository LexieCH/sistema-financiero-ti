<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('terceros', function (Blueprint $table) {

            $table->string('banco')->nullable()->after('direccion');

            $table->string('tipo_cuenta')->nullable()->after('banco');

            $table->string('numero_cuenta')->nullable()->after('tipo_cuenta');

        });
    }

    public function down(): void
    {
        Schema::table('terceros', function (Blueprint $table) {

            $table->dropColumn([
                'banco',
                'tipo_cuenta',
                'numero_cuenta'
            ]);

        });
    }
};