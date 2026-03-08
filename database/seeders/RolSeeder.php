<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::updateOrCreate(
            ['nombre' => 'Admin'],
            [
                'descripcion' => 'Administrador del sistema',
                'estado' => 'activo',
            ]
        );

        Rol::updateOrCreate(
            ['nombre' => 'Contador'],
            [
                'descripcion' => 'Gestión financiera',
                'estado' => 'activo',
            ]
        );

        Rol::updateOrCreate(
            ['nombre' => 'Solo lectura'],
            [
                'descripcion' => 'Acceso de consulta',
                'estado' => 'activo',
            ]
        );
    }
}