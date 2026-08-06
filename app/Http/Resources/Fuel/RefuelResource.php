<?php

namespace App\Http\Resources\Fuel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefuelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'route' => $this->whenLoaded('route', fn() => [
                'id' => $this->route->id,
                'code' => $this->route->code,
            ]),
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'driver' => $this->whenLoaded('driver', fn() => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ]),
            'driver_name' => $this->driver_name,
            'liters' => $this->liters,
            'amount' => $this->amount,
            'price_per_liter' => $this->price_per_liter,
            'payment_method' => $this->payment_method?->value ?? $this->payment_method,
            'ticket_photo' => $this->ticket_photo,
            'odometer' => $this->odometer,
            'folio' => $this->folio,
            'fuel_station' => $this->whenLoaded('fuelStation', fn() => [
                'id' => $this->fuelStation->id,
                'name' => $this->fuelStation->name,
            ]),
            'fuel_card' => $this->whenLoaded('fuelCard', fn() => [
                'id' => $this->fuelCard->id,
                'folio' => $this->fuelCard->folio,
            ]),
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
