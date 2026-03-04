<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rols')->insert([
            [
                'nombre' => 'Admin',
                'descripcion' => 'Administrador del sistema',
                'estado' => 'activo'
            ],
            [
                'nombre' => 'Contador',
                'descripcion' => 'Gestión financiera',
                'estado' => 'activo'
            ],
            [
                'nombre' => 'Solo lectura',
                'descripcion' => 'Acceso de consulta',
                'estado' => 'activo'
            ]
        ]);
    }
}