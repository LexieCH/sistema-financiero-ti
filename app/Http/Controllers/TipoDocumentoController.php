<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Models\TipoMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoDocumentoController extends Controller
{
    public function create()
    {
        return redirect()->route('tipos-documentos.index');
    }

    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $tipos = TipoDocumento::with('tipoMovimiento')
            ->where(function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId)
                    ->orWhereNull('empresa_id');
            })
            ->orderBy('nombre')
            ->get();

        $tiposMovimiento = TipoMovimiento::orderBy('nombre')->get();

        return view('tipos_documentos.index', compact('tipos', 'tiposMovimiento'));
    }

    public function store(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|in:compra,venta,interno',
            'usa_iva' => 'required|boolean',
            'credito_fiscal' => 'required|boolean',
            'genera_movimiento' => 'required|boolean',
            'tipo_movimiento_id' => 'nullable|exists:tipo_movimientos,id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        TipoDocumento::create([
            'empresa_id' => $empresaId,
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'usa_iva' => $request->boolean('usa_iva'),
            'credito_fiscal' => $request->boolean('credito_fiscal'),
            'genera_movimiento' => $request->boolean('genera_movimiento'),
            'tipo_movimiento_id' => $request->tipo_movimiento_id,
            'estado' => $request->estado,
        ]);

        return redirect()->route('tipos-documentos.index')
            ->with('success', 'Tipo de documento creado correctamente');
    }

    public function edit(TipoDocumento $tipos_documento)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($tipos_documento->empresa_id !== null && (int) $tipos_documento->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $tiposMovimiento = TipoMovimiento::orderBy('nombre')->get();

        return view('tipos_documentos.edit', [
            'tipoDocumento' => $tipos_documento,
            'tiposMovimiento' => $tiposMovimiento,
        ]);
    }

    public function show(TipoDocumento $tipos_documento)
    {
        return redirect()->route('tipos-documentos.edit', $tipos_documento);
    }

    public function update(Request $request, TipoDocumento $tipos_documento)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($tipos_documento->empresa_id !== null && (int) $tipos_documento->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|in:compra,venta,interno',
            'usa_iva' => 'required|boolean',
            'credito_fiscal' => 'required|boolean',
            'genera_movimiento' => 'required|boolean',
            'tipo_movimiento_id' => 'nullable|exists:tipo_movimientos,id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        if ($tipos_documento->empresa_id === null) {
            $tipos_documento->empresa_id = $empresaId;
        }

        $tipos_documento->update([
            'empresa_id' => $tipos_documento->empresa_id,
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'usa_iva' => $request->boolean('usa_iva'),
            'credito_fiscal' => $request->boolean('credito_fiscal'),
            'genera_movimiento' => $request->boolean('genera_movimiento'),
            'tipo_movimiento_id' => $request->tipo_movimiento_id,
            'estado' => $request->estado,
        ]);

        return redirect()->route('tipos-documentos.index')
            ->with('success', 'Tipo de documento actualizado correctamente');
    }

    public function destroy(TipoDocumento $tipos_documento)
    {
        $empresaId = Auth::user()->empresa_id;

        if ($tipos_documento->empresa_id !== null && (int) $tipos_documento->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $tipos_documento->update([
            'estado' => 'inactivo',
        ]);

        return redirect()->route('tipos-documentos.index')
            ->with('editado', 'Tipo de documento desactivado correctamente');
    }
}
