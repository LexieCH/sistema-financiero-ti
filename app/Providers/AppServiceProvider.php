<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Movimiento;
use App\Observers\MovimientoObserver;
use App\Models\Documento;
use App\Observers\DocumentoObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Logout;

use App\Helpers\BitacoraHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Observer de movimientos
        Movimiento::observe(MovimientoObserver::class);

        Documento::observe(DocumentoObserver::class);

        //INICIO DE SESIÓN
        Event::listen(Login::class, function ($event) {

            BitacoraHelper::registrar(
                'Sistema',
                'login',
                $event->user->id,
                'Usuario inició sesión'
            );

        });
        // Bitácora cuando usuario cierra sesión
        Event::listen(Logout::class, function ($event) {

            BitacoraHelper::registrar(
                'Sistema',
                'logout',
                $event->user->id,
                'Usuario cerró sesión'
            );

        });
    }
}

