<?php

namespace App\Http\Resources\Incident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'time' => $this->time,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'driver' => $this->whenLoaded('driver', fn() => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ]),
            'driver_name' => $this->driver_name,
            'type' => $this->type,
            'location' => $this->location,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => $this->status,
            'cost' => $this->cost,
            'involves_third_party' => $this->involves_third_party,
            'third_party_data' => $this->third_party_data,
            'photo' => $this->photo,
            'resolved_at' => $this->resolved_at,
            'resolved_by' => $this->resolved_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
