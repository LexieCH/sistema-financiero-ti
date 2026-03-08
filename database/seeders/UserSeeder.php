<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::where('rut_empresa', '77.123.456-7')->first();

        if (!$empresa) {
            $empresa = Empresa::create([
                'rut_empresa' => '77.123.456-7',
                'nombre_fantasia' => 'TCVIT',
                'razon_social' => 'TCVIT SpA',
                'direccion' => 'Concepción',
                'telefono' => '412345678',
                'email' => 'contacto@TCVIT.cl',
                'giro' => 'Informatica',
                'estado' => 'activa',
                'creada_por' => null,
            ]);
        }

        $rolAdmin = Rol::firstOrCreate(
            ['nombre' => 'Admin'],
            [
                'descripcion' => 'Administrador del sistema',
                'estado' => 'activo',
            ]
        );

        User::updateOrCreate([
            'email' => 'admin@sistema.cl',
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('123456'),
            'empresa_id' => $empresa->id,
            'rol_id' => $rolAdmin->id,
            'estado' => 'activo'
        ]);
    }
}