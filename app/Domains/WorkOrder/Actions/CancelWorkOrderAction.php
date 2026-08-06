<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderCancelled;

class CancelWorkOrderAction
{
    public function execute(int $id, string $reason, int $userId): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if (in_array($wo->status, ['completado', 'cerrado', 'cancelado'])) {
            throw new \DomainException('No se puede cancelar una orden ya completada, cerrada o cancelada.');
        }

        $wo->update(['status' => 'cancelado', 'rejection_reason' => $reason]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden cancelada: ' . $reason,
            'user_id' => $userId,
        ]);

        event(new WorkOrderCancelled($wo));

        return $wo;
    }
}
