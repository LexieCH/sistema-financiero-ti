<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Rol::where('nombre', 'Admin')->first();
        $contador = Rol::where('nombre', 'Contador')->first();
        $lectura = Rol::where('nombre', 'Solo lectura')->first();

        $modulos = Modulo::where('estado', 'activo')->get();

        if (!$admin || !$contador || !$lectura || $modulos->isEmpty()) {
            return;
        }

        foreach ($modulos as $modulo) {
            Permiso::updateOrCreate(
                ['rol_id' => $admin->id, 'modulo_id' => $modulo->id],
                ['lectura' => true, 'crear' => true, 'editar' => true, 'eliminar' => true]
            );

            $contadorModulos = [
                'movimientos',
                'terceros',
                'documentos',
                'tipos-documentos',
                'pagos',
                'centros-costos',
                'proyectos',
                'bitacora',
            ];

            $contadorFull = in_array($modulo->nombre, $contadorModulos, true);
            $contadorLectura = $contadorFull || $modulo->nombre === 'permisos';

            Permiso::updateOrCreate(
                ['rol_id' => $contador->id, 'modulo_id' => $modulo->id],
                [
                    'lectura' => $contadorLectura,
                    'crear' => $contadorFull,
                    'editar' => $contadorFull,
                    'eliminar' => $contadorFull,
                ]
            );

            Permiso::updateOrCreate(
                ['rol_id' => $lectura->id, 'modulo_id' => $modulo->id],
                ['lectura' => true, 'crear' => false, 'editar' => false, 'eliminar' => false]
            );
        }
    }
}
