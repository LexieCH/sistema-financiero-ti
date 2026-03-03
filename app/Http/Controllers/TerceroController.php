<?php

namespace App\Http\Controllers;

use App\Models\Tercero;
use Illuminate\Http\Request;

class TerceroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresaId = 1;

        $terceros = Tercero::where('empresa_id', $empresaId)
                            ->latest()
                            ->get();
        return view('terceros.index',compact('terceros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'razon_social' => 'required|string|max:200',
        'rut'          => 'nullable|string|max:20',
        'tipo'         => 'required|in:cliente,proveedor,ambos',
    ]);

    Tercero::create([
        'empresa_id'   => 1, // 🔥 temporal
        'razon_social' => $request->razon_social,
        'rut'          => $request->rut,
        'tipo'         => $request->tipo,
        'direccion'    => $request->direccion,
        'telefono'     => $request->telefono,
        'email'        => $request->email,
        'estado'       => true
    ]);

    return redirect()->route('terceros.index')
        ->with('guardado', 'Tercero creado correctamente');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
