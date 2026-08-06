<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;

class SubmitForApprovalAction
{
    public function execute(int $id, int $userId): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'draft') {
            throw new \DomainException('Solo órdenes en borrador pueden enviarse a aprobación.');
        }

        $wo->update(['status' => 'pending_approval']);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden enviada a aprobación',
            'user_id' => $userId,
        ]);

        return $wo;
    }
}
