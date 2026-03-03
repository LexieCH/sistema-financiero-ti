<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $empresas = Empresa::latest()->get();

        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación tercer intento
        $request->validate([
            'nombre_fantasia' => 'required|string|max:150',
            'rut_empresa'     => 'nullable|string|max:20',
            'razon_social'    => 'nullable|string|max:150',
            'giro'            => 'nullable|string|max:150'
        ]);

        try {

            Empresa::create([
                'nombre_fantasia' => $request->nombre_fantasia,
                'rut_empresa'     => $request->rut_empresa,
                'razon_social'    => $request->razon_social,
                'giro'            => $request->giro,
                'estado'          => 'activa',
                'creada_por'      => Auth::id() // multiempresa profesional
            ]);

            return redirect()->route('empresas.index')
                ->with('guardado', 'Empresa creada correctamente');

        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', 'Error al guardar empresa: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return response()->json($empresa); // para modal edit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre_fantasia' => 'required|string|max:150',
            'rut_empresa'     => 'nullable|string|max:20',
            'razon_social'    => 'nullable|string|max:150',
            'giro'            => 'nullable|string|max:150',
            'estado'          => 'required'
        ]);

        try {

            $empresa = Empresa::findOrFail($id);

            $empresa->update([
                'nombre_fantasia' => $request->nombre_fantasia,
                'rut_empresa'     => $request->rut_empresa,
                'razon_social'    => $request->razon_social,
                'giro'            => $request->giro,
                'estado'          => $request->estado
            ]);

            return redirect()->route('empresas.index')
                ->with('editado', 'Empresa actualizada correctamente');

        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $empresa = Empresa::findOrFail($id);
            $empresa->delete(); // soft delete recomendado

            return redirect()->route('empresas.index')
                ->with('eliminado', 'Empresa eliminada');

        } catch (\Exception $e) {

            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}