<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;

class DeleteWorkOrderAction
{
    public function execute(int $id): bool
    {
        $wo = WorkOrder::findOrFail($id);
        return $wo->delete();
    }
}
