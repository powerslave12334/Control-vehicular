<?php

namespace App\Shared\Listeners;

use App\Jobs\WorkOrder\NotifyWorkOrderPendingApprovalJob;
use App\Domains\WorkOrder\Events\WorkOrderCreated;

class DispatchWorkOrderPendingApprovalJobListener
{
    public function handle(WorkOrderCreated $event): void
    {
        NotifyWorkOrderPendingApprovalJob::dispatch($event->workOrder);
    }
}
