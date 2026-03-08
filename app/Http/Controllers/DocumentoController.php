<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\MetodoPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\BitacoraHelper;

class DocumentoController extends Controller
{

    public function index()
    {
        // Tomamos la empresa del usuario para mantener todo separado por multiempresa.
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

        // Validamos lo mínimo para que el documento no quede incompleto.

        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
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

            // El folio se calcula solo para no repetir números en la misma empresa.
            $folio = Documento::where('empresa_id', $empresaId)
                ->max('folio') ?? 0;
            $folio = $folio + 1;

            // Importante: crear documento no crea movimiento automático.
            $documento = Documento::create([
                'empresa_id' => $empresaId,
                'usuario_id' => Auth::id(),
                'tipo_documento_id' => $request->tipo_documento_id,
                'folio' => $folio,
                'fecha_emision' => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'monto_neto' => $request->monto_neto,
                'iva' => $iva,
                'total' => $total,
                'estado' => 'pendiente'
            ]);

            BitacoraHelper::registrar(
                'Documento',
                'crear',
                $documento->id,
                'Se creó documento folio '.$documento->folio
            );

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

        // Editar documento también recalcula IVA y total para mantener consistencia.

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

        // Se aplica borrado lógico para no perder historial contable.

        $documento = Documento::findOrFail($id);

        $documento->update([
            'estado' => 'anulado',
        ]);

        return redirect()->route('documentos.index')
            ->with('editado','Documento anulado correctamente');

    }

}