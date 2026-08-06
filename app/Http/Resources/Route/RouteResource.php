<?php

namespace App\Http\Resources\Route;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'date' => $this->date,
            'week' => $this->week,
            'driver' => $this->whenLoaded('driver', fn() => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ]),
            'driver_name' => $this->driver_name,
            'assistant_name' => $this->assistant_name,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'vehicle_plate' => $this->vehicle_plate,
            'client_name' => $this->client_name,
            'city' => $this->city,
            'state' => $this->state,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'distance_km' => $this->distance_km,
            'estimated_duration' => $this->estimated_duration,
            'planned_km' => $this->planned_km,
            'actual_km' => $this->actual_km,
            'start_odometer' => $this->start_odometer,
            'end_odometer' => $this->end_odometer,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'cancelled_at' => $this->cancelled_at,
            'cancellation_reason' => $this->cancellation_reason,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
