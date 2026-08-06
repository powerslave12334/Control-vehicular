<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderAssigned;

class AssignWorkOrderAction
{
    public function execute(int $id, int $providerId, int $userId): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'aprobado') {
            throw new \DomainException('La orden debe estar aprobada antes de asignarse a un taller.');
        }

        $wo->update(['status' => 'asignado', 'assigned_to' => $providerId]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden asignada a proveedor #' . $providerId,
            'user_id' => $userId,
        ]);

        event(new WorkOrderAssigned($wo));

        return $wo;
    }
}
