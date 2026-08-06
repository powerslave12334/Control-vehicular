<?php

namespace App\Jobs\Vehicle;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVehicleImageJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Vehicle $vehicle, public readonly string $imagePath) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Vehicle image queued for processing', [
            'vehicle_id' => $this->vehicle->id,
            'path' => $this->imagePath,
        ]);
    }
}
