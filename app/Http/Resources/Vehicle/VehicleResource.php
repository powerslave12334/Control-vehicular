<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'plate' => $this->plate,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'vehicle_type' => $this->vehicle_type,
            'status' => $this->status?->value ?? $this->status,
            'color' => $this->color,
            'vin' => $this->vin,
            'engine' => $this->engine,
            'fuel_type' => $this->fuel_type,
            'tank_capacity' => $this->tank_capacity,
            'current_odometer' => $this->current_odometer,
            'cargo_capacity' => $this->cargo_capacity,
            'gps_installed' => $this->gps_installed,
            'acquisition_date' => $this->acquisition_date?->format('Y-m-d'),
            'acquisition_cost' => $this->acquisition_cost,
            'last_maintenance' => $this->last_maintenance?->format('Y-m-d'),
            'next_maintenance' => $this->next_maintenance?->format('Y-m-d'),
            'assigned_driver_id' => $this->assigned_driver_id,
            'assigned_driver' => $this->whenLoaded('assignedDriver', fn() => $this->assignedDriver ? [
                'id' => $this->assignedDriver->id,
                'name' => $this->assignedDriver->name,
            ] : null),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
