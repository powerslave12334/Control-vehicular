<?php

namespace App\Domains\WorkOrder\Actions;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\Models\WorkOrderPart;
use App\Domains\WorkOrder\Models\WorkOrderTimeline;
use App\Domains\WorkOrder\DTO\WorkOrderPartData;
use App\Domains\Part\Actions\StockMovementAction;
use App\Domains\Part\DTO\PartMovementData;
use App\Domains\WorkOrder\Events\WorkOrderPartsUpdated;

class AddPartToWorkOrderAction
{
    public function __construct(protected StockMovementAction $stockMovementAction) {}

    public function execute(WorkOrderPartData $data, int $userId): WorkOrderPart
    {
        $wo = WorkOrder::findOrFail($data->work_order_id);

        if (!in_array($wo->status, ['aprobado', 'asignado', 'in_progress'])) {
            throw new \DomainException('Solo se pueden agregar refacciones a órdenes activas.');
        }

        if ($data->source === 'inventory' && $data->part_id) {
            $this->stockMovementAction->execute(PartMovementData::fromArray([
                'part_id' => $data->part_id,
                'quantity' => $data->quantity,
                'movement_type' => 'out',
                'user_id' => $userId,
                'reference_type' => 'work_order',
                'reference_id' => $wo->id,
                'unit_cost' => $data->unit_cost,
                'notes' => 'Usada en WO #' . $wo->code,
            ]));
        }

        $part = WorkOrderPart::create($data->toArray());

        $wo->update(['parts_cost' => $wo->parts()->sum('total_cost')]);

        WorkOrderTimeline::create([
            'work_order_id' => $wo->id,
            'event_type' => 'part_added',
            'description' => "Refacción agregada: {$data->description} x{$data->quantity}",
            'user_id' => $userId,
        ]);

        event(new WorkOrderPartsUpdated($wo));

        return $part;
    }
}
