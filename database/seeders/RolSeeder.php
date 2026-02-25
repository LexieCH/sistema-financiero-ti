<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create([
            'nombre'=>'System Admin',
            'descripcion'=>'Administrador principal del sistema',
            'estado'=>'activo'
        ]);

        Rol::create([
            'nombre'=>'Admin Empresa',
            'descripcion'=>'Administra su empresa',
            'estado'=>'activo'
        ]);

        Rol::create([
            'nombre'=>'Contador',
            'descripcion'=>'Gestiona movimientos financieros',
            'estado'=>'activo'
        ]);

        Rol::create([
            'nombre'=>'Usuario',
            'descripcion'=>'Usuario básico del sistema',
            'estado'=>'activo'
        ]);
    }
}