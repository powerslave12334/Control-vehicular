<?php

namespace App\Shared\Listeners;

use App\Jobs\Maintenance\NotifyOverdueMaintenanceJob;
use App\Domains\Maintenance\Events\MaintenanceOverdue;

class DispatchMaintenanceOverdueJobListener
{
    public function handle(MaintenanceOverdue $event): void
    {
        NotifyOverdueMaintenanceJob::dispatch($event->maintenance);
    }
}
