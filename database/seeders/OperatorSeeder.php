<?php

namespace Database\Seeders;

use App\Domains\Operator\Models\Operator;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $operators = [
            ['name' => 'Juan Pérez Sánchez', 'license_type' => 'Chofer A', 'phone' => '555-019-2831', 'status' => 'Activo'],
            ['name' => 'Carlos Gómez Torres', 'license_type' => 'Chofer B', 'phone' => '555-014-9281', 'status' => 'Activo'],
            ['name' => 'Luis Martínez Díaz', 'license_type' => 'Chofer A', 'phone' => '555-018-4720', 'status' => 'Activo'],
            ['name' => 'Sofía Rodríguez Luna', 'license_type' => 'Chofer B', 'phone' => '555-011-3948', 'status' => 'Activo'],
        ];

        foreach ($operators as $o) {
            Operator::create($o);
        }
    }
}
