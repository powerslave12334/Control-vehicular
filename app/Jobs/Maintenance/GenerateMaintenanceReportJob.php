<?php

namespace App\Jobs\Maintenance;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMaintenanceReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Maintenance $maintenance) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Maintenance report generated', [
            'maintenance_id' => $this->maintenance->id,
            'vehicle_id' => $this->maintenance->vehicle_id,
            'type' => $this->maintenance->type,
            'cost' => $this->maintenance->cost,
        ]);
    }
}
