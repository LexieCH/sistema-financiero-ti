<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('metodo_pagos')->insert([
            ['empresa_id'=>1,'nombre'=>'Efectivo','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Transferencia','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Débito','estado'=>1],
            ['empresa_id'=>1,'nombre'=>'Crédito','estado'=>1],
        ]);
    }
}
