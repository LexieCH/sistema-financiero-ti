<?php

namespace App\Http\Controllers; 

use App\Models\Documento;
use App\Models\Movimiento;
use App\Models\TipoDocumento;
use App\Models\TipoMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;




class DocumentoController extends Controller 
{
    public function index()
    {
        $tipos = TipoDocumento::where('estado','activo')->get();

        $documentos = Documento::latest()->get();

        $totalPendiente = Documento::where('estado','pendiente')->sum('total');
        $totalPagado = Documento::where('estado','pagado')->sum('total');
        $totalGeneral = Documento::sum('total');

        return view('documentos.index', compact(
            'tipos',
            'documentos',
            'totalPendiente',
            'totalPagado',
            'totalGeneral'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'fecha_emision'     => 'required|date',
            'monto_neto'        => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $tipoDocumento = TipoDocumento::findOrFail($request->tipo_documento_id);
            $iva = 0;
            $total = $request->monto_neto;

            if ($tipoDocumento->usa_iva) {
                $iva = round($request->monto_neto * 0.19, 2);
                $total = $request->monto_neto + $iva;
            }
            // 1ro  Crear Documento
            $documento = Documento::create([
                'empresa_id'        => 1, // temporal
                'tercero_id'        => $request->tercero_id,
                'usuario_id'        => Auth::id(),
                'tipo_documento_id' => $request->tipo_documento_id,
                'folio'             => $request->folio,
                'fecha_emision'     => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'monto_neto'        => $request->monto_neto,
                'iva'               => $iva,
                'total'             => $total,
                'estado'            => 'pendiente'

            ]);

            // 2do Si el tipo de documento no genera el  movimiento inmediato

        if ($tipoDocumento->genera_movimiento) {

            Movimiento::create([
                'empresa_id'       => 1,
                'usuario_id'       => Auth::id(),
                'tipo_movimiento_id'=> $tipoDocumento->tipo_movimiento_id,
                'categoria_id'     => null,
                'metodo_pago_id'   => null,
                'centro_costo_id'  => null,
                'socio_id'         => null,
                'documento_id'     => $documento->id,
                'tercero_id'       => $request->tercero_id,
                'monto'            => $documento->total,
                'fecha'            => now(),
                'referencia'        => 'Movimiento automático desde documento',
                'descripcion'      => $tipoDocumento->nombre,
                'estado'           => 'confirmado'
            ]);
        }

            DB::commit();

            return redirect()->route('documentos.index')
                ->with('guardado','Documento creado correctamente');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error','Error: '.$e->getMessage());
        }
    }

    public function marcarPagado($id)
    {
        return DB::transaction(function () use ($id) {

            $documento = Documento::with('tipoDocumento')->findOrFail($id);

            if ($documento->estado === 'pagado') {
                return redirect()->route('documentos.index')
                    ->with('error', 'El documento ya está pagado.');
            }

            $tipoDocumento = $documento->tipoDocumento;

            // Si el tipo documento no genera movimiento, solo cambia estado
            if ($tipoDocumento->genera_movimiento && $tipoDocumento->tipo_movimiento_id) {

                Movimiento::create([
                    'empresa_id'         => $documento->empresa_id,
                    'usuario_id'         => auth()->id(),
                    'tipo_movimiento_id' => $tipoDocumento->tipo_movimiento_id,
                    'documento_id'       => $documento->id,
                    'tercero_id'         => $documento->tercero_id ?? null,
                    'fecha'              => now(),
                    'monto'              => $documento->total,
                    'descripcion'        => 'Pago documento #' . $documento->id,
                    'estado'             => 'confirmado'
                ]);
            }

            $documento->update([
                'estado' => 'pagado'
            ]);

            return redirect()->route('documentos.index')
                ->with('success', 'Documento pagado correctamente.');
        });
    }
}