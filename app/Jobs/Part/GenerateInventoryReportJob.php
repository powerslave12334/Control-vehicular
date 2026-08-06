<?php

namespace App\Jobs\Part;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateInventoryReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $stats = \App\Domains\Part\Models\Part::selectRaw('
            COUNT(*) as total_parts,
            SUM(stock) as total_stock,
            SUM(stock * unit_price) as total_inventory_value
        ')->first();

        \Illuminate\Support\Facades\Log::info('Inventory report generated', $stats->toArray());
    }
}
