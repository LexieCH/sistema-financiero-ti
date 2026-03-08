<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\RolSeeder;
use Database\Seeders\EmpresaSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TipoDocumentoSeeder;
use Database\Seeders\TipoMovimientoSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\MetodoPagoSeeder;
use Database\Seeders\TerceroSeeder;
use Database\Seeders\ModuloSeeder;
use Database\Seeders\PermisoSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            EmpresaSeeder::class,   // primero crear empresas
            UserSeeder::class,      // luego usuarios
            TipoDocumentoSeeder::class,
            TipoMovimientoSeeder::class,
            CategoriaSeeder::class,
            MetodoPagoSeeder::class,
            TerceroSeeder::class,
            ModuloSeeder::class,
            PermisoSeeder::class,
        ]);
    }
}