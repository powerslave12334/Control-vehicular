<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\DTO\WorkOrderData;

class CreateWorkOrderAction
{
    public function execute(WorkOrderData $data): WorkOrder
    {
        return WorkOrder::create($data->toArray());
    }
}
