<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Movimiento;
use App\Models\TipoDocumento;
use App\Models\MetodoPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{

    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $tipos = TipoDocumento::where('empresa_id', $empresaId)
            ->where('estado','activo')
            ->get();

        $documentos = Documento::where('empresa_id', $empresaId)
            ->latest()
            ->get();

        $metodosPago = MetodoPago::where('empresa_id',$empresaId)->get();

        $totalPendiente = Documento::where('empresa_id',$empresaId)
            ->where('estado','pendiente')
            ->sum('total');

        $totalPagado = Documento::where('empresa_id',$empresaId)
            ->where('estado','pagado')
            ->sum('total');

        $totalGeneral = Documento::where('empresa_id',$empresaId)
            ->sum('total');

        return view('documentos.index', compact(
            'tipos',
            'documentos',
            'metodosPago',
            'totalPendiente',
            'totalPagado',
            'totalGeneral'
        ));
    }



    public function store(Request $request)
    {

        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'tercero_id'        => 'required|exists:terceros,id',
            'fecha_emision'     => 'required|date',
            'monto_neto'        => 'required|numeric|min:0',
        ]);


        DB::beginTransaction();

        try {

            $empresaId = Auth::user()->empresa_id;

            $tipoDocumento = TipoDocumento::findOrFail($request->tipo_documento_id);

            $iva = 0;
            $total = $request->monto_neto;

            if ($tipoDocumento->usa_iva) {
                $iva = round($request->monto_neto * 0.19, 2);
                $total = $request->monto_neto + $iva;
            }


            // generar folio automático
            $folio = Documento::where('empresa_id', $empresaId)
                ->max('folio') + 1;


            // 1 Crear documento
            $documento = Documento::create([
                'empresa_id'        => $empresaId,
                'tercero_id'        => $request->tercero_id,
                'usuario_id'        => Auth::id(),
                'tipo_documento_id' => $request->tipo_documento_id,
                'folio'             => $folio,
                'fecha_emision'     => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'monto_neto'        => $request->monto_neto,
                'iva'               => $iva,
                'total'             => $total,
                'estado'            => 'pendiente'
            ]);


            // 2 Crear movimiento automático si corresponde
            if ($tipoDocumento->genera_movimiento) {

                Movimiento::create([

                    'empresa_id'         => $empresaId,
                    'usuario_id'         => Auth::id(),

                    'tipo_movimiento_id' => $tipoDocumento->tipo_movimiento_id,

                    'categoria_id'       => null,
                    'metodo_pago_id'     => null,
                    'centro_costo_id'    => null,
                    'socio_id'           => null,

                    'documento_id'       => $documento->id,
                    'tercero_id'         => $request->tercero_id,

                    'monto'              => $documento->total,
                    'fecha'              => now(),

                    'referencia'         => 'DOC-'.$documento->folio,

                    'descripcion'        => 'Movimiento generado por '.$tipoDocumento->nombre,

                    'estado'             => 'confirmado'

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



    public function edit($id)
    {
        $documento = Documento::findOrFail($id);

        $tipos = TipoDocumento::where('estado','activo')->get();

        return view('documentos.edit', compact('documento','tipos'));
    }



    public function update(Request $request, $id)
    {

        $documento = Documento::findOrFail($id);

        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'fecha_emision' => 'required|date',
            'monto_neto' => 'required|numeric|min:0',
        ]);

        $tipoDocumento = TipoDocumento::findOrFail($request->tipo_documento_id);

        $iva = 0;
        $total = $request->monto_neto;

        if ($tipoDocumento->usa_iva) {
            $iva = round($request->monto_neto * 0.19, 2);
            $total = $request->monto_neto + $iva;
        }

        $documento->update([
            'tipo_documento_id' => $request->tipo_documento_id,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'monto_neto' => $request->monto_neto,
            'iva' => $iva,
            'total' => $total
        ]);

        return redirect()->route('documentos.index')
            ->with('success','Documento actualizado correctamente');

    }



    public function destroy($id)
    {

        $documento = Documento::findOrFail($id);

        $documento->delete(); // soft delete

        return redirect()->route('documentos.index')
            ->with('success','Documento eliminado');

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