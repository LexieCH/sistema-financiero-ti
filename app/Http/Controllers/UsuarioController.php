<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Rol;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
        abort(403, 'No autorizado');
        }
        $usuarios = User::with(['empresa','rol'])->get();
        $empresas = Empresa::where('estado','activa')->get();
        $roles = Rol::where('estado','activo')->get();

        return view('usuarios.index', compact('usuarios','empresas','roles'));
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
        if(auth()->user()->rol->nombre !== 'System Admin'){
        abort(403, 'No autorizado');
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'empresa_id' => $request->empresa_id,
            'rol_id' => $request->rol_id,
            'estado' => 'activo'
        ]);

        return redirect()->route('usuarios.index')->with('success','Usuario creado');
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
        if(auth()->user()->rol->nombre !== 'System Admin'){
        abort(403, 'No autorizado');
    }
    $usuario = User::findOrFail($id);

    $usuario->update([
        'name' => $request->name,
        'email' => $request->email,
        'empresa_id' => $request->empresa_id,
        'rol_id' => $request->rol_id,
        'estado' => $request->estado
    ]);

    return redirect()->route('usuarios.index')->with('success','Usuario actualizado');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
        abort(403, 'No autorizado');
        }
        $usuario = User::findOrFail($id);
        $usuario->estado = 'inactivo';
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success','Usuario desactivado');
    }
}