<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;

class RestoreMaintenanceAction
{
    public function execute(int $id): Maintenance
    {
        $maintenance = Maintenance::withTrashed()->findOrFail($id);
        $maintenance->restore();
        return $maintenance;
    }
}
