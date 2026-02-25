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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('tercero_id')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('tipo_documento_id')->constrained('tipo_documentos');

            $table->integer('folio')->nullable();

            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();

            $table->decimal('monto_neto',12,2)->default(0);
            $table->decimal('iva',12,2)->default(0);
            $table->decimal('total',12,2)->default(0);

            $table->enum('estado',['pendiente','pagado','anulado','vencido'])->default('pendiente');

            $table->string('pdf_url')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
