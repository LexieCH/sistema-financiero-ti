<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\MetodoPago;
use App\Models\Tercero;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $documentos = Documento::with(['tipoDocumento', 'tercero'])
            ->where('empresa_id', $empresaId)
            ->latest()
            ->get();

        $terceros = Tercero::where('empresa_id', $empresaId)
            ->where('estado', 1)
            ->orderBy('razon_social')
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
            'terceros',
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
            'tercero_id'        => 'required|exists:terceros,id',
            'fecha_emision'     => 'required|date|in:' . now()->toDateString(),
            'monto_neto'        => 'required|numeric|min:0',
            'pdf'              => 'nullable|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {

            $empresaId = Auth::user()->empresa_id;

            $terceroValido = Tercero::where('empresa_id', $empresaId)
                ->where('id', $request->tercero_id)
                ->exists();

            if (!$terceroValido) {
                throw new \Exception('El tercero seleccionado no pertenece a la empresa actual.');
            }

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
            $pdfPath = null;

            if ($request->hasFile('pdf')) {
                $pdfPath = $request->file('pdf')->store('documentos_pdf', 'public');
            }

            $documento = Documento::create([
                'empresa_id' => $empresaId,
                'tercero_id' => $request->tercero_id,
                'usuario_id' => Auth::id(),
                'tipo_documento_id' => $request->tipo_documento_id,
                'folio' => $folio,
                'fecha_emision' => $request->fecha_emision,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'monto_neto' => $request->monto_neto,
                'iva' => $iva,
                'total' => $total,
                'estado' => 'pendiente',
                'pdf_url' => $pdfPath,
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

        $empresaId = Auth::user()->empresa_id;

        $tipos = TipoDocumento::where('estado','activo')->get();
        $terceros = Tercero::where('empresa_id', $empresaId)
            ->where('estado', 1)
            ->orderBy('razon_social')
            ->get();

        return view('documentos.edit', compact('documento','tipos', 'terceros'));
    }



    public function update(Request $request, $id)
    {

        // Editar documento también recalcula IVA y total para mantener consistencia.

        $documento = Documento::findOrFail($id);
        $empresaId = Auth::user()->empresa_id;

        $terceroValido = Tercero::where('empresa_id', $empresaId)
            ->where('id', $request->tercero_id)
            ->exists();

        if (!$terceroValido) {
            return back()->with('error', 'El tercero seleccionado no pertenece a la empresa actual.');
        }

        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documentos,id',
            'tercero_id' => 'required|exists:terceros,id',
            'fecha_emision' => 'required|date',
            'monto_neto' => 'required|numeric|min:0',
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $tipoDocumento = TipoDocumento::findOrFail($request->tipo_documento_id);

        $iva = 0;
        $total = $request->monto_neto;

        if ($tipoDocumento->usa_iva) {
            $iva = round($request->monto_neto * 0.19, 2);
            $total = $request->monto_neto + $iva;
        }

        $pdfPath = $documento->pdf_url;

        if ($request->hasFile('pdf')) {
            if (!empty($documento->pdf_url)) {
                Storage::disk('public')->delete($documento->pdf_url);
            }

            $pdfPath = $request->file('pdf')->store('documentos_pdf', 'public');
        }

        $documento->update([
            'tipo_documento_id' => $request->tipo_documento_id,
            'tercero_id' => $request->tercero_id,
            'fecha_emision' => $request->fecha_emision,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'monto_neto' => $request->monto_neto,
            'iva' => $iva,
            'total' => $total,
            'pdf_url' => $pdfPath,
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