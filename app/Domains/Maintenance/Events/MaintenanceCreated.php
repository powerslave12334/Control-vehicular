<?php

namespace App\Domains\Maintenance\Events;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Foundation\Events\Dispatchable;

class MaintenanceCreated
{
    use Dispatchable;

    public function __construct(public Maintenance $maintenance) {}
}
