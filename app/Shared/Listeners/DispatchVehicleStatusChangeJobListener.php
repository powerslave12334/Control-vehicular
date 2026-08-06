<?php

namespace App\Shared\Listeners;

use App\Jobs\Vehicle\NotifyVehicleStatusChangeJob;
use App\Domains\Vehicle\Events\VehicleStatusChanged;

class DispatchVehicleStatusChangeJobListener
{
    public function handle(VehicleStatusChanged $event): void
    {
        NotifyVehicleStatusChangeJob::dispatch($event->vehicle, $event->oldStatus, $event->newStatus);
    }
}
