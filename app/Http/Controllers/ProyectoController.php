<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{

    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

        $proyectos = Proyecto::where('empresa_id',$empresaId)->get();

        return view('proyectos.index', compact('proyectos'));
    }


    public function create()
    {
        return view('proyectos.create');
    }


    public function store(Request $request)
    {
        // Validación simple para no guardar proyectos vacíos.

        $request->validate([
            'nombre' => 'required|string|max:200',
            'presupuesto' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        Proyecto::create([
            'empresa_id' => Auth::user()->empresa_id,
            'nombre' => $request->nombre,
            'presupuesto' => $request->presupuesto,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_finalizacion' => $request->fecha_finalizacion,
            'estado' => true,
        ]);

        return redirect()->route('proyectos.index')
            ->with('success','Proyecto creado correctamente');
    }

    public function edit(Proyecto $proyecto)
    {
        if ((int) $proyecto->empresa_id !== (int) Auth::user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return view('proyectos.edit', compact('proyecto'));
    }

    public function show(Proyecto $proyecto)
    {
        return redirect()->route('proyectos.edit', $proyecto);
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        if ((int) $proyecto->empresa_id !== (int) Auth::user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:200',
            'presupuesto' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|boolean',
        ]);

        $proyecto->update([
            'nombre' => $request->nombre,
            'presupuesto' => $request->presupuesto,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_finalizacion' => $request->fecha_finalizacion,
            'estado' => $request->boolean('estado'),
        ]);

        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto actualizado correctamente');
    }

    public function destroy(Proyecto $proyecto)
    {
        if ((int) $proyecto->empresa_id !== (int) Auth::user()->empresa_id) {
            abort(403, 'No autorizado');
        }

            $proyecto->update([
                'estado' => false,
            ]);

        return redirect()->route('proyectos.index')
                ->with('editado', 'Proyecto desactivado correctamente.');
    }

}