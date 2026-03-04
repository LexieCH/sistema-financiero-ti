<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.cl',
            'password' => bcrypt('123456'),
            'empresa_id' => 1,
            'rol_id' => 1,
            'estado' => 'activo'
        ]);
    }
}