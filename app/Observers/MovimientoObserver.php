<?php

namespace App\Observers;

use App\Models\Movimiento;
use App\Helpers\BitacoraHelper;

class MovimientoObserver
{

    public function created(Movimiento $movimiento)
    {

        BitacoraHelper::registrar(
            'Movimiento',
            'crear',
            $movimiento->id,
            'Se registró un movimiento por $' . number_format($movimiento->monto,0,',','.')
        );

    }

    public function updated(Movimiento $movimiento)
    {

        BitacoraHelper::registrar(
            'Movimiento',
            'editar',
            $movimiento->id,
            'Se actualizó el movimiento #' . $movimiento->id
        );

    }

    public function deleted(Movimiento $movimiento)
    {

        BitacoraHelper::registrar(
            'Movimiento',
            'eliminar',
            $movimiento->id,
            'Se eliminó el movimiento #' . $movimiento->id
        );

    }

}