<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderRejected;

class RejectWorkOrderAction
{
    public function execute(int $id, string $reason, int $userId): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'pendiente_aprobacion') {
            throw new \DomainException('Solo órdenes pendientes de aprobación pueden ser rechazadas.');
        }

        $wo->update(['status' => 'rechazado', 'rejection_reason' => $reason, 'approved_by' => $userId]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden rechazada: ' . $reason,
            'user_id' => $userId,
        ]);

        event(new WorkOrderRejected($wo));

        return $wo;
    }
}
