<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderStarted;

class StartWorkOrderAction
{
    public function execute(int $id, int $userId): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if (!in_array($wo->status, ['asignado', 'en_progreso'])) {
            throw new \DomainException('La orden debe estar asignada para iniciar.');
        }

        $wo->update(['status' => 'en_progreso', 'started_at' => now()]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Trabajo iniciado por el taller',
            'user_id' => $userId,
        ]);

        event(new WorkOrderStarted($wo));

        return $wo;
    }
}
