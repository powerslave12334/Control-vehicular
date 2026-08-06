<?php

namespace App\Shared\Listeners;

use App\Jobs\Fuel\NotifyAbnormalConsumptionJob;
use App\Domains\Fuel\Events\AbnormalFuelConsumptionDetected;

class DispatchAbnormalFuelConsumptionJobListener
{
    public function handle(AbnormalFuelConsumptionDetected $event): void
    {
        NotifyAbnormalConsumptionJob::dispatch($event->refuel, $event->actualKmPerLiter);
    }
}
