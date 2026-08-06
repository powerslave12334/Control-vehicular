<?php

namespace App\Jobs\Vehicle;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateVehicleHistoryReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;
    public int $backoff = 30;

    public function __construct(public readonly Vehicle $vehicle) {}

    public function handle(): void
    {
        $data = [
            'vehicle' => $this->vehicle->toArray(),
            'routes_count' => $this->vehicle->routes()->count(),
            'refuels_count' => $this->vehicle->refuels()->count(),
            'maintenances_count' => $this->vehicle->maintenances()->count(),
            'incidents_count' => $this->vehicle->incidents()->count(),
        ];

        \Illuminate\Support\Facades\Log::info('Vehicle history report generated', [
            'vehicle_id' => $this->vehicle->id,
            'plate' => $this->vehicle->plate,
            'report' => $data,
        ]);
    }
}
