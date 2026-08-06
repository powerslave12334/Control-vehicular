<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\DTO\MaintenanceData;

class CreateMaintenanceAction
{
    public function execute(MaintenanceData $data): Maintenance
    {
        return Maintenance::create($data->toArray());
    }
}
