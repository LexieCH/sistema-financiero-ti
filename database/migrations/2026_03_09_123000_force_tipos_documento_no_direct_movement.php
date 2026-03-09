<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tipo_documentos')->update([
            'genera_movimiento' => 0,
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // No revertimos este ajuste porque la regla de negocio define que
        // los movimientos financieros se generan desde pagos.
    }
};
