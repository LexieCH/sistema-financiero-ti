<?php

namespace App\Observers;

use App\Models\Pago;
use App\Helpers\BitacoraHelper;

class PagoObserver
{
    public function created(Pago $pago): void
    {
        BitacoraHelper::registrar(
            'Pago',
            'crear',
            $pago->id,
            'Se registró pago #' . $pago->id . ' por $' . number_format($pago->monto, 0, ',', '.')
        );
    }

    public function updated(Pago $pago): void
    {
        BitacoraHelper::registrar(
            'Pago',
            'editar',
            $pago->id,
            'Se actualizó el pago #' . $pago->id
        );
    }

    public function deleted(Pago $pago): void
    {
        BitacoraHelper::registrar(
            'Pago',
            'eliminar',
            $pago->id,
            'Se eliminó el pago #' . $pago->id
        );
    }
}
