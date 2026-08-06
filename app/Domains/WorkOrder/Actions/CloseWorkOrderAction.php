<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Models\WorkOrderEvaluation;
use App\Domains\WorkOrder\DTO\WorkOrderEvaluationData;
use App\Domains\WorkOrder\Events\WorkOrderClosed;

class CloseWorkOrderAction
{
    public function execute(int $id, WorkOrderEvaluationData $evaluation): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'completado') {
            throw new \DomainException('Solo órdenes completadas pueden cerrarse.');
        }

        if (!$wo->total_cost) {
            throw new \DomainException('No se puede cerrar una orden sin costos registrados.');
        }

        WorkOrderEvaluation::create($evaluation->toArray());

        $wo->update(['status' => 'cerrado', 'closed_at' => now()]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => 'Orden cerrada con evaluación',
            'user_id' => $evaluation->evaluated_by,
        ]);

        event(new WorkOrderClosed($wo));

        return $wo;
    }
}
