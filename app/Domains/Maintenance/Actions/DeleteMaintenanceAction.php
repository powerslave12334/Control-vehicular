<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;

class DeleteMaintenanceAction
{
    public function execute(int $id): bool
    {
        $maintenance = Maintenance::findOrFail($id);
        return $maintenance->delete();
    }
}
