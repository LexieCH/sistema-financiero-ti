<?php

namespace App\Helpers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraHelper
{
    public static function registrar($modulo, $accion, $registroId = null, $descripcion = null)
    {

        Bitacora::create([
            'empresa_id' => Auth::user()->empresa_id,
            'usuario_id' => Auth::id(),
            'modulo' => $modulo,
            'accion' => $accion,
            'registro_id' => $registroId,
            'descripcion' => $descripcion
        ]);

    }
}