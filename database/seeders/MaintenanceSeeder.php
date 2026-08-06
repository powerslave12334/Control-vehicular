<?php

namespace Database\Seeders;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        Maintenance::insert([
            [
                'date' => '2026-06-10',
                'vehicle_id' => 1,
                'type' => 'Preventivo',
                'description' => 'Servicio de los 45,000 Km: Cambio de aceite, filtros de aire y gasolina, balanceo y rotación de llantas.',
                'cost' => 2450,
                'workshop' => 'Servicio Automotriz Fast-Track',
                'evidence' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'date' => '2026-07-12',
                'vehicle_id' => 3,
                'type' => 'Correctivo',
                'description' => 'Reparación del sistema de gas LP, cambio de válvulas de seguridad y ajuste de carburador por pérdida de potencia.',
                'cost' => 5120,
                'workshop' => 'Taller Especializado Multigas',
                'evidence' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?auto=format&fit=crop&w=400&q=80',
            ],
        ]);
    }
}
