<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            ['nombre' => 'empresas', 'descripcion' => 'Gestión de empresas'],
            ['nombre' => 'usuarios', 'descripcion' => 'Gestión de usuarios'],
            ['nombre' => 'permisos', 'descripcion' => 'Permisos por módulo'],
            ['nombre' => 'movimientos', 'descripcion' => 'Caja y movimientos'],
            ['nombre' => 'terceros', 'descripcion' => 'Clientes y proveedores'],
            ['nombre' => 'documentos', 'descripcion' => 'Facturación y documentos'],
            ['nombre' => 'tipos-documentos', 'descripcion' => 'Mantenedor de tipos de documento'],
            ['nombre' => 'pagos', 'descripcion' => 'Gestión de pagos'],
            ['nombre' => 'centros-costos', 'descripcion' => 'Centros de costo'],
            ['nombre' => 'proyectos', 'descripcion' => 'Gestión de proyectos'],
            ['nombre' => 'bitacora', 'descripcion' => 'Bitácora del sistema'],
        ];

        foreach ($modulos as $modulo) {
            Modulo::updateOrCreate(
                ['nombre' => $modulo['nombre']],
                [
                    'descripcion' => $modulo['descripcion'],
                    'estado' => 'activo',
                ]
            );
        }
    }
}
