<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use App\Models\Pago;

public function store(Request $request)
{
    $request->validate([
        'documento_id' => 'required|exists:documentos,id',
        'metodo_pago_id' => 'required|exists:metodo_pagos,id',
        'fecha_pago' => 'required|date',
        'monto' => 'required|numeric|min:0.01'
    ]);

    DB::transaction(function () use ($request) {

        $documento = Documento::lockForUpdate()->findOrFail($request->documento_id);

        // Validaciones críticas
        if ($documento->estado === 'anulado') {
            abort(403, 'No se pueden registrar pagos en documentos anulados.');
        }

        $saldoPendiente = $documento->saldoPendiente();

        if ($request->monto > $saldoPendiente) {
            abort(422, 'El monto excede el saldo pendiente.');
        }

        // Crear pago
        Pago::create([
            'empresa_id' => auth()->user()->empresa_id,
            'documento_id' => $documento->id,
            'usuario_id' => auth()->id(),
            'metodo_pago_id' => $request->metodo_pago_id,
            'fecha_pago' => $request->fecha_pago,
            'monto' => $request->monto,
            'observacion' => $request->observacion
        ]);

        // Recalcular saldo
        $nuevoSaldo = $documento->saldoPendiente();

        if ($nuevoSaldo <= 0) {
            $documento->update(['estado' => 'pagado']);
        } else {
            $documento->update(['estado' => 'pendiente']);
        }

    });

    return redirect()->back()->with('success', 'Pago registrado correctamente.');
}