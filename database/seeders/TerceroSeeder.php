<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerceroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('terceros')->insert([
            [
                'empresa_id'=>1,
                'rut'=>'11111111-1',
                'razon_social'=>'Cliente Demo',
                'tipo'=>'cliente',
                'estado'=>1
            ],
            [
                'empresa_id'=>1,
                'rut'=>'22222222-2',
                'razon_social'=>'Proveedor Demo',
                'tipo'=>'proveedor',
                'estado'=>1
            ]
        ]);
    }
}