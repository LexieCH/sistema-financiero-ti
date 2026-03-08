<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Documento;
use App\Models\Pago;

class PagoController extends Controller
{

    public function index()
    {
        // Vista de pagos con métricas simples para control rápido.
        $pagos = Pago::with('documento')
            ->latest()
            ->get();

        $pagosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->sum('monto');

        $cantidadPagos = Pago::count();

        $promedioPago = Pago::avg('monto');

        return view('pagos.index', compact(
            'pagos',
            'pagosMes',
            'cantidadPagos',
            'promedioPago'
        ));
    }

    public function store(Request $request)
    {
        // Reglas mínimas para registrar un pago válido.
        $request->validate([
            'documento_id' => 'required|exists:documentos,id',
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01'
        ]);

        DB::transaction(function () use ($request) {

            // lockForUpdate evita problemas si dos usuarios pagan al mismo tiempo.
            $documento = Documento::lockForUpdate()->findOrFail($request->documento_id);

            // No dejamos pagar documentos anulados.
            if ($documento->estado === 'anulado') {
                abort(403, 'No se pueden registrar pagos en documentos anulados.');
            }

            $saldoPendiente = $documento->saldoPendiente();

            if ($request->monto > $saldoPendiente) {
                abort(422, 'El monto excede el saldo pendiente.');
            }

            // Si todo está bien, registramos el pago.
            Pago::create([
                'empresa_id' => Auth::user()->empresa_id,
                'documento_id' => $documento->id,
                'usuario_id' => Auth::id(),
                'metodo_pago_id' => $request->metodo_pago_id,
                'fecha_pago' => $request->fecha_pago,
                'monto' => $request->monto,
                'observacion' => $request->observacion
            ]);

            // Después del pago recalculamos estado del documento.
            $nuevoSaldo = $documento->saldoPendiente();

            if ($nuevoSaldo <= 0) {
                $documento->update(['estado' => 'pagado']);
            } else {
                $documento->update(['estado' => 'pendiente']);
            }

        });

        return redirect()->back()->with('success', 'Pago registrado correctamente.');
    }

}