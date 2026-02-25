<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'rut_empresa' => '11111111-1',
            'nombre_fantasia' => 'Ingeniería y Soporte TI',
            'razon_social' => 'Ingeniería y Soporte TI Chile Ltda',
            'giro' => 'Servicios informáticos',
            'estado' => 'activa'
        ]);
    }
}