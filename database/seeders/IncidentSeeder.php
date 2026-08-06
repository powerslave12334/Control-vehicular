<?php

namespace Database\Seeders;

use App\Domains\Incident\Models\Incident;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        Incident::insert([
            [
                'date' => '2026-07-11', 'time' => '14:20',
                'vehicle_id' => 3, 'driver_id' => 3,
                'driver_name' => 'Luis Martínez Díaz',
                'description' => 'Falla en alternador. El vehículo no encendió tras realizar una entrega a un cliente en Toluca.',
                'severity' => 'Media', 'status' => 'Resuelta',
                'photo' => 'https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'date' => '2026-07-15', 'time' => '09:05',
                'vehicle_id' => 2, 'driver_id' => 2,
                'driver_name' => 'Carlos Gómez Torres',
                'description' => 'Ponchadura de llanta trasera derecha en autopista a Cuernavaca por presencia de clavos.',
                'severity' => 'Baja', 'status' => 'Reportada',
                'photo' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&w=400&q=80',
            ],
        ]);
    }
}
