<?php

use App\Models\Documento;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('documentos:marcar-vencidos', function () {
    $hoy = now()->toDateString();

    $actualizados = Documento::query()
        ->where('estado', 'pendiente')
        ->whereNotNull('fecha_vencimiento')
        ->whereDate('fecha_vencimiento', '<', $hoy)
        ->update(['estado' => 'vencido']);

    $this->info("Documentos vencidos actualizados: {$actualizados}");
})->purpose('Marca como vencido los documentos pendientes fuera de plazo');

Schedule::command('documentos:marcar-vencidos')->dailyAt('00:10');
