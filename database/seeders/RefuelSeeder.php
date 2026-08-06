<?php

namespace Database\Seeders;

use App\Domains\Fuel\Models\Refuel;
use Illuminate\Database\Seeder;

class RefuelSeeder extends Seeder
{
    public function run(): void
    {
        Refuel::insert([
            [
                'date' => '2026-07-14',
                'vehicle_id' => 2, 'driver_id' => 2,
                'driver_name' => 'Carlos Gómez Torres',
                'liters' => 42, 'amount' => 980,
                'price_per_liter' => 23.33,
                'payment_method' => 'Tarjeta',
                'ticket_photo' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=400&q=80',
                'odometer' => 62720,
            ],
            [
                'date' => '2026-07-10',
                'vehicle_id' => 1, 'driver_id' => 1,
                'driver_name' => 'Juan Pérez Sánchez',
                'liters' => 35, 'amount' => 805,
                'price_per_liter' => 23.0,
                'payment_method' => 'Tarjeta',
                'ticket_photo' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=400&q=80',
                'odometer' => 45110,
            ],
        ]);
    }
}
