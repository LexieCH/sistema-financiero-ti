<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        if (Auth::user()->rol->nombre !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $usuarios = User::with(['empresa', 'rol'])
            ->orderBy('name')
            ->get();

        $empresas = Empresa::where('estado', 'activa')->get();

        $roles = Rol::where('estado', 'activo')->get();

        return view('usuarios.index', compact(
            'usuarios',
            'empresas',
            'roles'
        ));
    }


    /**
     * Formulario de creación
     */
    public function create()
    {
        //
    }


    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        if (Auth::user()->rol->nombre !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol_id' => 'required|exists:rols,id',
            'empresa_id' => 'nullable|exists:empresas,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'empresa_id' => $request->empresa_id,
            'rol_id' => $request->rol_id,
            'estado' => 'activo'
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Mostrar usuario
     */
    public function show(string $id)
    {
        return redirect()->route('usuarios.edit', $id);
    }


    /**
     * Editar usuario
     */
    public function edit(string $id)
    {
        if (Auth::user()->rol->nombre !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $usuario = User::findOrFail($id);
        $empresas = Empresa::where('estado', 'activa')->get();
        $roles = Rol::where('estado', 'activo')->get();

        return view('usuarios.edit', compact('usuario', 'empresas', 'roles'));
    }


    /**
     * Actualizar usuario
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->rol->nombre !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol_id' => 'required|exists:rols,id',
            'empresa_id' => 'nullable|exists:empresas,id',
            'password' => 'nullable|min:6'
        ]);

        $datos = [
            'name' => $request->name,
            'email' => $request->email,
            'empresa_id' => $request->empresa_id,
            'rol_id' => $request->rol_id,
            'estado' => $request->estado
        ];

        if ($request->filled('password')) {
            $datos['password'] = bcrypt($request->password);
        }

        $usuario->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado');
    }


    /**
     * Desactivar usuario
     */
    public function destroy(string $id)
    {
        if (Auth::user()->rol->nombre !== 'Admin') {
            abort(403, 'No autorizado');
        }

        $usuario = User::findOrFail($id);

        $usuario->estado = 'inactivo';

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario desactivado');
    }
}