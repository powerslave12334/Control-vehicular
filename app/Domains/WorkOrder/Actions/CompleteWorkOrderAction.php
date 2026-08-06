<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\Events\WorkOrderCompleted;

class CompleteWorkOrderAction
{
    public function execute(int $id, float $laborCost, ?float $mileage = null, ?string $diagnosis = null): WorkOrder
    {
        $wo = WorkOrder::findOrFail($id);

        if ($wo->status !== 'en_progreso') {
            throw new \DomainException('Solo órdenes en progreso pueden completarse.');
        }

        $partsCost = $wo->parts()->sum('total_cost');
        $totalCost = $laborCost + $partsCost;

        $update = [
            'status' => 'completado',
            'labor_cost' => $laborCost,
            'parts_cost' => $partsCost,
            'total_cost' => $totalCost,
            'completed_at' => now(),
        ];

        if ($mileage) {
            $update['mileage_at_completion'] = $mileage;
        }
        if ($diagnosis) {
            $update['diagnosis'] = $diagnosis;
        }

        $wo->update($update);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'status_change',
            'description' => "Trabajo completado. Costo total: \${$totalCost}",
            'user_id' => $wo->requested_by,
        ]);

        event(new WorkOrderCompleted($wo));

        return $wo;
    }
}
