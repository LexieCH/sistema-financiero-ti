<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class TipoMovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_movimientos')->insert([
            [
            'nombre' => 'Ingreso',
            'naturaleza' => 'ingreso',
            'afecta_caja' => 1,
            'requiere_socio' => 0
            ],
            [
            'nombre' => 'Egreso',
            'naturaleza' => 'egreso',
            'afecta_caja' => 1,
            'requiere_socio' => 0
            ],
            [
            'nombre' => 'Retiro socio',
            'naturaleza' => 'egreso',
            'afecta_caja' => 1,
            'requiere_socio' => 1
            ],
            [
            'nombre' => 'Aporte capital',
            'naturaleza' => 'ingreso',
            'afecta_caja' => 1,
            'requiere_socio' => 1
            ],
            [
            'nombre' => 'Transferencia interna',
            'naturaleza' => 'interno',
            'afecta_caja' => 0,
            'requiere_socio' => 0
            ]
            ]);
    }
}
