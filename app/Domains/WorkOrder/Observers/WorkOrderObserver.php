<?php

namespace App\Domains\WorkOrder\Observers;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Events\WorkOrderCreated;
use App\Domains\WorkOrder\Events\WorkOrderDeleted;
use App\Domains\WorkOrder\Events\WorkOrderRestored;
use App\Shared\Services\AuditService;

class WorkOrderObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(WorkOrder $workOrder): void
    {
        event(new WorkOrderCreated($workOrder));
        $this->audit->log('created', $workOrder, "Orden #{$workOrder->code} creada");
    }

    public function updated(WorkOrder $workOrder): void
    {
        $changes = $workOrder->getChanges();
        if (isset($changes['status'])) {
            $original = $workOrder->getRawOriginal('status');
            $this->audit->log('status_changed', $workOrder, "WO #{$workOrder->code}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $workOrder, "WO #{$workOrder->code} actualizada", $changes);
        }
    }

    public function deleted(WorkOrder $workOrder): void
    {
        event(new WorkOrderDeleted($workOrder));
        $this->audit->log('deleted', $workOrder, "WO #{$workOrder->code} eliminada");
    }

    public function restored(WorkOrder $workOrder): void
    {
        event(new WorkOrderRestored($workOrder));
        $this->audit->log('restored', $workOrder, "WO #{$workOrder->code} restaurada");
    }
}
