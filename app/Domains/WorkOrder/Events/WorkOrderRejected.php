<?php

namespace App\Domains\WorkOrder\Events;

use App\Domains\WorkOrder\Models\WorkOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class WorkOrderRejected
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public WorkOrder $workOrder) {}
}
