<?php
namespace App\Http\Controllers; 

use App\Models\CentroCosto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class CentroCostoController extends Controller
{

    public function index()
    {
        $empresaId = auth()->user()->empresa_id;

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
        $proyectos = Proyecto::where('empresa_id', auth()->user()->empresa_id)->get();

        return view('centros_costos.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'proyecto_id' => 'required'
        ]);

        CentroCosto::create([
            'empresa_id' => auth()->user()->empresa_id,
            'proyecto_id' => $request->proyecto_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true
        ]);

        return redirect()->route('centros-costos.index')
            ->with('success','Centro de costo creado');
    }

}