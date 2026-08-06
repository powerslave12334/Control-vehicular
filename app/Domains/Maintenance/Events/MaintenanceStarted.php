<?php

namespace App\Domains\Maintenance\Events;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class MaintenanceStarted
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Maintenance $maintenance) {}
}
