<?php

namespace Database\Seeders;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'brand' => 'Nissan', 'model' => 'NP300', 'year' => 2022,
                'plate' => 'AAA-1234-B', 'fuel_type' => 'Gasolina',
                'cargo_capacity' => '1.2 Toneladas', 'tank_capacity' => 80,
                'gps_installed' => true, 'vin' => '1N6BF01Y3LCA12345',
                'status' => 'Activa', 'current_odometer' => 45200,
                'last_maintenance' => '2026-06-10', 'next_maintenance' => '2026-09-10',
                'incidents_count' => 0, 'authorized_fuel' => 120,
                'notes' => 'Unidad asignada a la sucursal Centro para entregas locales de purificadoras.',
            ],
            [
                'brand' => 'Ford', 'model' => 'Transit', 'year' => 2021,
                'plate' => 'BBB-4536-C', 'fuel_type' => 'Diesel',
                'cargo_capacity' => '2.5 Toneladas', 'tank_capacity' => 100,
                'gps_installed' => true, 'vin' => '1FTYR2Y84KCA67890',
                'status' => 'Activa', 'current_odometer' => 62800,
                'last_maintenance' => '2026-05-15', 'next_maintenance' => '2026-08-15',
                'incidents_count' => 1, 'authorized_fuel' => 200,
                'notes' => 'Vehículo pesado usado para transporte de equipos de ósmosis inversa industriales.',
            ],
            [
                'brand' => 'Chevrolet', 'model' => 'Express', 'year' => 2020,
                'plate' => 'CCC-783-D', 'fuel_type' => 'Gas',
                'cargo_capacity' => '1.8 Toneladas', 'tank_capacity' => 90,
                'gps_installed' => true, 'vin' => '1GCVK1Y89LCA11223',
                'status' => 'En mantenimiento', 'current_odometer' => 89100,
                'last_maintenance' => '2026-07-12', 'next_maintenance' => '2026-10-12',
                'incidents_count' => 2, 'authorized_fuel' => 150,
                'notes' => 'En taller por cambio de balatas y afinación del sistema de gas.',
            ],
            [
                'brand' => 'Toyota', 'model' => 'Hilux', 'year' => 2023,
                'plate' => 'DDD-0312-E', 'fuel_type' => 'Gasolina',
                'cargo_capacity' => '1.0 Toneladas', 'tank_capacity' => 75,
                'gps_installed' => true, 'vin' => 'MR0FR22Y5LCA44556',
                'status' => 'Activa', 'current_odometer' => 24500,
                'last_maintenance' => '2026-06-25', 'next_maintenance' => '2026-09-25',
                'incidents_count' => 0, 'authorized_fuel' => 100,
                'notes' => 'Asignada a supervisor de calidad para visitas de auditoría a franquicias.',
            ],
        ];

        foreach ($vehicles as $v) {
            Vehicle::create($v);
        }
    }
}
