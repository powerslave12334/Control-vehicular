<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'type' => $this->type,
            'category' => $this->category,
            'description' => $this->description,
            'cost' => $this->cost,
            'workshop' => $this->workshop,
            'workshop_id' => $this->workshop_id,
            'evidence' => $this->evidence,
            'odometer' => $this->odometer,
            'scheduled_date' => $this->scheduled_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
