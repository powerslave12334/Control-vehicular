<?php

namespace App\Jobs\Fuel;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateFuelStatisticsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $stats = \App\Domains\Fuel\Models\Refuel::selectRaw('
            COUNT(*) as total_refuels,
            SUM(liters) as total_liters,
            SUM(amount) as total_cost,
            AVG(price_per_liter) as avg_price_per_liter
        ')->first();

        \Illuminate\Support\Facades\Log::info('Fuel statistics generated', $stats->toArray());
    }
}
