<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.cl',
            'password' => Hash::make('12345678'),
            'empresa_id' => 1,
            'rol_id' => 1
        ]);
    }
}