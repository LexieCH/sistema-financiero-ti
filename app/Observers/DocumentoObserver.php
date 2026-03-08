<?php

namespace App\Observers;

use App\Models\Documento;
use App\Helpers\BitacoraHelper;

class DocumentoObserver
{

    public function created(Documento $documento)
    {

        BitacoraHelper::registrar(
            'Documento',
            'crear',
            $documento->id,
            'Se creó documento folio '.$documento->folio
        );

    }


    public function updated(Documento $documento)
    {

        BitacoraHelper::registrar(
            'Documento',
            'editar',
            $documento->id,
            'Se actualizó documento folio '.$documento->folio
        );

    }


    public function deleted(Documento $documento)
    {

        BitacoraHelper::registrar(
            'Documento',
            'eliminar',
            $documento->id,
            'Se eliminó documento folio '.$documento->folio
        );

    }

}