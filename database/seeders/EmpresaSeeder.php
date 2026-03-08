<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::updateOrCreate([
            'rut_empresa' => '77.123.456-7',
        ], [
            'nombre_fantasia' => 'TCVIT',
            'razon_social' => 'TCVIT SpA',
            'direccion' => 'Concepción',
            'telefono' => '412345678',
            'email' => 'contacto@TCVIT.cl',
            'giro' => 'Informatica',
            'estado' => 'activa',
            'creada_por' => null
        ]);

        Empresa::updateOrCreate([
            'rut_empresa' => '76.999.888-5',
        ], [
            'nombre_fantasia' => 'Ingeniería y Soporte Chile',
            'razon_social' => 'Ingeniería y Soporte Chile Ltda',
            'direccion' => 'Santiago',
            'telefono' => '221234567',
            'email' => 'contacto@soporte.cl',
            'giro' => 'Servicios informáticos',
            'estado' => 'activa',
            'creada_por' => null
        ]);
    }
}