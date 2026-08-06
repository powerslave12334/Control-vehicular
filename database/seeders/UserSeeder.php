<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
use App\Models\Module;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@aguainmaculada.com',
            'password' => bcrypt('admin123'),
            'role' => 'Administrador del sistema',
            'status' => 'Activo',
        ]);

        $admin->modules()->sync(Module::pluck('id'));
    }
}
