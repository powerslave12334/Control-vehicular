<?php

namespace App\Http\Resources\WorkOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'operator' => $this->whenLoaded('operator', fn() => [
                'id' => $this->operator->id,
                'name' => $this->operator->name,
            ]),
            'maintenance' => $this->whenLoaded('maintenance', fn() => [
                'id' => $this->maintenance->id,
                'type' => $this->maintenance->type,
            ]),
            'provider' => $this->whenLoaded('provider', fn() => [
                'id' => $this->provider->id,
                'name' => $this->provider->name,
            ]),
            'description' => $this->description,
            'diagnosis' => $this->diagnosis,
            'priority' => $this->priority,
            'status' => $this->status,
            'estimated_cost' => $this->estimated_cost,
            'labor_cost' => $this->labor_cost,
            'parts_cost' => $this->parts_cost,
            'total_cost' => $this->total_cost,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'closed_at' => $this->closed_at,
            'mileage_at_request' => $this->mileage_at_request,
            'mileage_at_completion' => $this->mileage_at_completion,
            'notes' => $this->notes,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
