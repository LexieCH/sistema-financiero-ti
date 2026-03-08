<?php
namespace App\Http\Controllers; 

use App\Models\CentroCosto;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentroCostoController extends Controller
{

    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $centros = \App\Models\CentroCosto::with('proyecto')
            ->where('empresa_id', $empresaId)
            ->get();

        $total = $centros->count();
        $activos = $centros->where('estado', true)->count();
        $inactivos = $centros->where('estado', false)->count();

        return view('centros_costos.index', compact(
            'centros',
            'total',
            'activos',
            'inactivos'
        ));
    }

    public function create()
    {
        $proyectos = Proyecto::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('centros_costos.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $request->validate([
            'nombre' => 'required',
            'proyecto_id' => 'required|exists:proyectos,id'
        ]);

        $proyecto = Proyecto::where('empresa_id', $empresaId)
            ->where('id', $request->proyecto_id)
            ->firstOrFail();

        CentroCosto::create([
            'empresa_id' => $empresaId,
            'proyecto_id' => $proyecto->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true
        ]);

        return redirect()->route('centros-costos.index')
            ->with('success','Centro de costo creado');
    }

    public function edit(CentroCosto $centros_costo)
    {
        $empresaId = Auth::user()->empresa_id;

        if ((int) $centros_costo->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $proyectos = Proyecto::where('empresa_id', $empresaId)->get();

        return view('centros_costos.edit', [
            'centro' => $centros_costo,
            'proyectos' => $proyectos,
        ]);
    }

    public function show(CentroCosto $centros_costo)
    {
        return redirect()->route('centros-costos.edit', $centros_costo);
    }

    public function update(Request $request, CentroCosto $centros_costo)
    {
        $empresaId = Auth::user()->empresa_id;

        if ((int) $centros_costo->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'proyecto_id' => 'required|exists:proyectos,id',
            'descripcion' => 'nullable|string|max:200',
            'estado' => 'required|boolean',
        ]);

        $proyecto = Proyecto::where('empresa_id', $empresaId)
            ->where('id', $request->proyecto_id)
            ->firstOrFail();

        $centros_costo->update([
            'proyecto_id' => $proyecto->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->boolean('estado'),
        ]);

        return redirect()->route('centros-costos.index')
            ->with('success', 'Centro de costo actualizado');
    }

    public function destroy(CentroCosto $centros_costo)
    {
        $empresaId = Auth::user()->empresa_id;

        if ((int) $centros_costo->empresa_id !== (int) $empresaId) {
            abort(403, 'No autorizado');
        }

        $centros_costo->update([
            'estado' => false,
        ]);

        return redirect()->route('centros-costos.index')
            ->with('editado', 'Centro de costo desactivado');
    }

    

}