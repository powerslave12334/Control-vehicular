<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderApproved;

class ApproveWorkOrderAction
{
    public function execute(int $id, int $approvedBy): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'pendiente_aprobacion') {
            throw new \DomainException('Solo órdenes pendientes de aprobación pueden ser aprobadas.');
        }

        $wo->update(['status' => 'aprobado', 'approved_by' => $approvedBy]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden aprobada',
            'user_id' => $approvedBy,
        ]);

        event(new WorkOrderApproved($wo));

        return $wo;
    }
}
