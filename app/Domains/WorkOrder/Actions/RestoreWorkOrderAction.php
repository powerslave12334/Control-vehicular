<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;

class RestoreWorkOrderAction
{
    public function execute(int $id): WorkOrder
    {
        $wo = WorkOrder::withTrashed()->findOrFail($id);
        $wo->restore();
        return $wo;
    }
}
