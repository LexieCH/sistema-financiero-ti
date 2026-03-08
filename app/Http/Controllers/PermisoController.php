<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $roles = Rol::where('estado', 'activo')->orderBy('nombre')->get();
        $roleId = (int) ($request->get('rol_id') ?: ($roles->first()->id ?? 0));

        $rolSeleccionado = $roles->firstWhere('id', $roleId);
        $modulos = Modulo::where('estado', 'activo')->orderBy('nombre')->get();

        $permisos = Permiso::where('rol_id', $roleId)
            ->get()
            ->keyBy('modulo_id');

        return view('permisos.index', compact(
            'roles',
            'rolSeleccionado',
            'modulos',
            'permisos'
        ));
    }

    public function update(Request $request, int $rol)
    {
        $rolModel = Rol::findOrFail($rol);

        $modulos = Modulo::where('estado', 'activo')->get();

        foreach ($modulos as $modulo) {
            $dataModulo = $request->input("permisos.{$modulo->id}", []);

            Permiso::updateOrCreate(
                [
                    'rol_id' => $rolModel->id,
                    'modulo_id' => $modulo->id,
                ],
                [
                    'lectura' => !empty($dataModulo['lectura']),
                    'crear' => !empty($dataModulo['crear']),
                    'editar' => !empty($dataModulo['editar']),
                    'eliminar' => !empty($dataModulo['eliminar']),
                ]
            );
        }

        return redirect()
            ->route('permisos.index', ['rol_id' => $rolModel->id])
            ->with('success', 'Permisos actualizados correctamente.');
    }
}
