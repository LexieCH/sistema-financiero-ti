<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Movimiento;
use App\Models\Tercero;
use App\Helpers\RutHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TerceroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresaId = Auth::user()->empresa_id;

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
    $empresaId = Auth::user()->empresa_id;
    $rut = RutHelper::normalizar($request->rut);

    if (!$rut || !RutHelper::esValido($rut)) {
        return back()->withInput()->withErrors([
            'rut' => 'El RUT del tercero no es válido.'
        ]);
    }

    $request->validate([

        'razon_social' => 'required|string|max:255',
        'rut' => [
            'required',
            'string',
            'max:20',
            Rule::unique('terceros', 'rut')->where(function ($query) use ($empresaId) {
                return $query->where('empresa_id', $empresaId);
            })
        ],
        'tipo' => 'required|in:cliente,proveedor,ambos',
        'direccion' => 'nullable|string|max:200',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email',

        'banco' => 'nullable|string|max:100',
        'tipo_cuenta' => 'nullable|string|max:50',
        'numero_cuenta' => 'nullable|string|max:50'

    ]);

    Tercero::create([
        'empresa_id'   => $empresaId,
        'razon_social' => $request->razon_social,
        'rut'          => $rut,
        'tipo'         => $request->tipo,
        'direccion'    => $request->direccion,
        'telefono'     => $request->telefono,
        'email'        => $request->email,
        'banco'        => $request->banco,
        'tipo_cuenta'  => $request->tipo_cuenta,
        'numero_cuenta'=> $request->numero_cuenta,
        'estado'       => true
    ]);

    return redirect()->route('terceros.index')
        ->with('guardado', 'Tercero creado correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tercero = Tercero::findOrFail($id);

        $movimientos = Movimiento::where('tercero_id', $id)
            ->latest('fecha')
            ->get();

        $documentoIds = $movimientos
            ->pluck('documento_id')
            ->filter()
            ->unique()
            ->values();

        $documentos = Documento::whereIn('id', $documentoIds)->get();

        $totalFacturado = $documentos->sum('total');

        return view('terceros.show', compact(
            'tercero',
            'documentos',
            'movimientos',
            'totalFacturado'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tercero = Tercero::findOrFail($id);
        return view('terceros.edit', compact('tercero'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresaId = Auth::user()->empresa_id;
        $rut = RutHelper::normalizar($request->rut);

        if (!$rut || !RutHelper::esValido($rut)) {
            return back()->withInput()->withErrors([
                'rut' => 'El RUT del tercero no es válido.'
            ]);
        }

        $request->validate([
            'razon_social' => 'required|string|max:255',
            'rut' => [
                'required',
                'string',
                'max:20',
                Rule::unique('terceros', 'rut')
                    ->where(function ($query) use ($empresaId) {
                        return $query->where('empresa_id', $empresaId);
                    })
                    ->ignore($id)
            ],
            'tipo' => 'required|in:cliente,proveedor,ambos',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'banco' => 'nullable|string|max:100',
            'tipo_cuenta' => 'nullable|string|max:50',
            'numero_cuenta' => 'nullable|string|max:50',
            'estado' => 'required|boolean'
        ]);

        $tercero = Tercero::findOrFail($id);

        $tercero->update([
            'razon_social' => $request->razon_social,
            'rut' => $rut,
            'tipo' => $request->tipo,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'banco' => $request->banco,
            'tipo_cuenta' => $request->tipo_cuenta,
            'numero_cuenta' => $request->numero_cuenta,
            'estado' => $request->boolean('estado')
        ]);

        return redirect()->route('terceros.index')
            ->with('editado', 'Tercero actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tercero = Tercero::findOrFail($id);
        $tercero->update([
            'estado' => false,
        ]);

        return redirect()->route('terceros.index')
            ->with('editado', 'Tercero desactivado correctamente');
    }
}
