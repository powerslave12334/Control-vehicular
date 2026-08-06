<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\DTO\MaintenanceData;
use App\Domains\Maintenance\Models\Maintenance;

class UpdateMaintenanceAction
{
    public function execute(int $id, MaintenanceData $data): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update($data->toArray());

        return $maintenance;
    }
}
