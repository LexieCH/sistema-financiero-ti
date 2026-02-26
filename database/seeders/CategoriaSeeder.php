<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            // INGRESOS
            ['empresa_id'=>1,'nombre'=>'Ventas','tipo'=>'ingreso','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Servicios','tipo'=>'ingreso','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Otros ingresos','tipo'=>'ingreso','estado'=>1],

            // EGRESOS
            ['empresa_id'=>1,'nombre'=>'Arriendo','tipo'=>'egreso','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Sueldos','tipo'=>'egreso','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Insumos','tipo'=>'egreso','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Servicios básicos','tipo'=>'egreso','estado'=>1],
        ]);
    }
}