<?php

namespace Database\Seeders;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Models\RouteStep;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        // Route 1: Programada - Toluca
        Route::create([
            'date' => '2026-07-15', 'week' => 'Semana 29',
            'driver_id' => 1, 'driver_name' => 'Juan Pérez Sánchez',
            'vehicle_id' => 1, 'vehicle_plate' => 'AAA-123-B',
            'client_name' => 'Franquicia Agua Inmaculada Toluca',
            'city' => 'Toluca', 'state' => 'Estado de México',
            'planned_km' => 140, 'actual_km' => 0, 'status' => 'Programada',
        ]);

        // Route 2: Completada - Cuernavaca
        $route2 = Route::create([
            'date' => '2026-07-14', 'week' => 'Semana 29',
            'driver_id' => 2, 'driver_name' => 'Carlos Gómez Torres',
            'vehicle_id' => 2, 'vehicle_plate' => 'BBB-456-C',
            'client_name' => 'Instalación Industrial Cuernavaca',
            'city' => 'Cuernavaca', 'state' => 'Morelos',
            'planned_km' => 190, 'actual_km' => 194, 'status' => 'Completada',
        ]);

        $route2->steps()->createMany([
            [
                'step_type' => 'departurePlant',
                'odometer' => 62600, 'fuel_level' => 90,
                'timestamp' => '2026-07-14 08:00:00',
                'photo' => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'arrivalClient',
                'timestamp' => '2026-07-14 10:15:00',
                'photo' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'startInstallation',
                'timestamp' => '2026-07-14 10:30:00',
                'photo' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'endInstallation',
                'timestamp' => '2026-07-14 15:45:00',
                'photo' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'refuel',
                'liters' => 42, 'amount' => 980, 'odometer' => 62720,
                'ticket_photo' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=400&q=80',
                'timestamp' => '2026-07-14 16:10:00',
            ],
            [
                'step_type' => 'arrivalPlant',
                'odometer' => 62794,
                'timestamp' => '2026-07-14 18:00:00',
            ],
        ]);

        // Route 3: Instalando - Puebla
        $route3 = Route::create([
            'date' => '2026-07-15', 'week' => 'Semana 29',
            'driver_id' => 3, 'driver_name' => 'Luis Martínez Díaz',
            'vehicle_id' => 4, 'vehicle_plate' => 'DDD-012-E',
            'client_name' => 'Soporte Técnico Puebla Centro',
            'city' => 'Puebla', 'state' => 'Puebla',
            'planned_km' => 260, 'actual_km' => 120, 'status' => 'Instalando',
        ]);

        $route3->steps()->createMany([
            [
                'step_type' => 'departurePlant',
                'odometer' => 24380, 'fuel_level' => 100,
                'timestamp' => '2026-07-15 07:15:00',
                'photo' => 'https://images.unsplash.com/photo-1617469167446-80e3ae459fc5?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'arrivalClient',
                'timestamp' => '2026-07-15 09:40:00',
                'photo' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'startInstallation',
                'timestamp' => '2026-07-15 10:00:00',
                'photo' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'step_type' => 'endInstallation',
                'timestamp' => '2026-07-15 15:00:00',
            ],
            [
                'step_type' => 'returnToPlant',
                'timestamp' => '2026-07-15 16:30:00',
            ],
            [
                'step_type' => 'arrivalPlant',
                'odometer' => 24640,
                'timestamp' => '2026-07-15 18:00:00',
            ],
        ]);
    }
}
