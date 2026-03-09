<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Documento;
use App\Models\Pago;
use App\Models\Movimiento;
use App\Models\TipoMovimiento;

class PagoController extends Controller
{

    public function index()
    {
        // Vista de pagos con métricas simples para control rápido.
        $empresaId = Auth::user()->empresa_id;

        $pagos = Pago::with('documento')
            ->where('empresa_id', $empresaId)
            ->latest()
            ->get();

        $pagosMes = Pago::where('empresa_id', $empresaId)
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $cantidadPagos = Pago::where('empresa_id', $empresaId)->count();

        $promedioPago = Pago::where('empresa_id', $empresaId)->avg('monto');

        $referencias = $pagos->map(fn ($pago) => 'PAGO-' . $pago->id)->values();

        $movimientosPorReferencia = Movimiento::query()
            ->where('empresa_id', $empresaId)
            ->whereIn('referencia', $referencias)
            ->pluck('id', 'referencia');

        return view('pagos.index', compact(
            'pagos',
            'pagosMes',
            'cantidadPagos',
            'promedioPago',
            'movimientosPorReferencia'
        ));
    }

    public function store(Request $request)
    {
        // Reglas mínimas para registrar un pago válido.
        $request->validate([
            'documento_id' => 'required|exists:documentos,id',
            'metodo_pago_id' => 'required|exists:metodo_pagos,id',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'condicion_pago' => 'nullable|in:total,30,60,90,120',
        ]);

        DB::transaction(function () use ($request) {

            // lockForUpdate evita problemas si dos usuarios pagan al mismo tiempo.
            $documento = Documento::lockForUpdate()->findOrFail($request->documento_id);

            // No dejamos pagar documentos anulados.
            if ($documento->estado === 'anulado') {
                abort(403, 'No se pueden registrar pagos en documentos anulados.');
            }

            $saldoPendiente = $documento->saldoPendiente();

            $condicionPago = $request->condicion_pago ?? 'total';

            if ($request->monto > $saldoPendiente) {
                abort(422, 'El monto excede el saldo pendiente.');
            }

            $observacion = $request->observacion;

            if ($condicionPago !== 'total') {
                $dias = (int) $condicionPago;
                $periodos = max(1, intdiv($dias, 30));

                $observacionBase = trim((string) $request->observacion);
                $sufijoPlan = "Plan {$dias} días ({$periodos} cuotas)";
                $observacion = $observacionBase !== ''
                    ? "{$observacionBase} | {$sufijoPlan}"
                    : $sufijoPlan;
            }

            // Registramos solo el pago realmente efectuado.
            $pago = Pago::create([
                'empresa_id' => Auth::user()->empresa_id,
                'documento_id' => $documento->id,
                'usuario_id' => Auth::id(),
                'metodo_pago_id' => $request->metodo_pago_id,
                'fecha_pago' => $request->fecha_pago,
                'monto' => $request->monto,
                'observacion' => $observacion
            ]);

            // Flujo obligatorio: cada pago genera su movimiento financiero automáticamente.
            $tipoMovimiento = $this->resolverTipoMovimientoParaPago($documento);

            if (!$tipoMovimiento) {
                abort(422, 'No hay tipo de movimiento disponible para registrar el pago.');
            }

            Movimiento::create([
                'empresa_id' => $documento->empresa_id,
                'usuario_id' => Auth::id(),
                'tipo_movimiento_id' => $tipoMovimiento->id,
                'categoria_id' => null,
                'metodo_pago_id' => $request->metodo_pago_id,
                'centro_costo_id' => null,
                'socio_id' => null,
                'documento_id' => $documento->id,
                'tercero_id' => $documento->tercero_id,
                'fecha' => $request->fecha_pago,
                'monto' => $request->monto,
                'referencia' => 'PAGO-' . $pago->id,
                'descripcion' => 'Pago documento #' . $documento->id,
                'estado' => 'confirmado',
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

    private function resolverTipoMovimientoParaPago(Documento $documento): ?TipoMovimiento
    {
        $documento->loadMissing('tipoDocumento');

        $tipoDocumento = $documento->tipoDocumento;

        $naturaleza = match ($tipoDocumento?->categoria) {
            'venta' => 'ingreso',
            'compra' => 'egreso',
            default => null,
        };

        if ($naturaleza) {
            return TipoMovimiento::query()
                ->where('estado', 1)
                ->where('naturaleza', $naturaleza)
                ->orderBy('id')
                ->first();
        }

        if ($tipoDocumento?->categoria === 'interno' && $tipoDocumento?->tipo_movimiento_id) {
            return TipoMovimiento::query()
                ->where('estado', 1)
                ->find($tipoDocumento->tipo_movimiento_id);
        }

        return null;
    }

}