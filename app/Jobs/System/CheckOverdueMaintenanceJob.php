<?php

namespace App\Jobs\System;

use App\Jobs\Maintenance\NotifyOverdueMaintenanceJob;
use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CheckOverdueMaintenanceJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $overdue = Maintenance::whereIn('status', ['pendiente', 'en_progreso'])
            ->where('scheduled_date', '<', now())
            ->get();

        foreach ($overdue as $maintenance) {
            NotifyOverdueMaintenanceJob::dispatch($maintenance);
        }
    }
}
