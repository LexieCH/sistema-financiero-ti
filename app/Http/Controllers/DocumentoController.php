<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    public function index()
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
            abort(403,'No autorizado');
        }

        $usuarios = User::with(['empresa','rol'])->get();

        $empresas = Empresa::where('estado','activa')->get();

        $roles = Rol::where('estado','activo')->get();

        return view('usuarios.index', compact(
            'usuarios',
            'empresas',
            'roles'
        ));
    }



    public function store(Request $request)
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
            abort(403,'No autorizado');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'empresa_id' => 'required|exists:empresas,id',
            'rol_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'empresa_id' => $request->empresa_id,
            'rol_id' => $request->rol_id,
            'estado' => 'activo'
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success','Usuario creado correctamente');
    }



    public function update(Request $request, string $id)
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
            abort(403,'No autorizado');
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$usuario->id,
            'empresa_id' => 'required|exists:empresas,id',
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'required'
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'empresa_id' => $request->empresa_id,
            'rol_id' => $request->rol_id,
            'estado' => $request->estado
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success','Usuario actualizado');
    }



    public function destroy(string $id)
    {
        if(auth()->user()->rol->nombre !== 'System Admin'){
            abort(403,'No autorizado');
        }

        $usuario = User::findOrFail($id);

        $usuario->estado = 'inactivo';

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success','Usuario desactivado');
    }

}