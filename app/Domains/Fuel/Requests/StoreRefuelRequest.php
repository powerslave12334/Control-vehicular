<?php

namespace App\Domains\Fuel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefuelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Domains\Fuel\Models\Refuel::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'liters' => ['required', 'numeric', 'min:0.1'],
            'amount' => ['required', 'numeric', 'min:0.1'],
            'payment_method' => ['required', 'string', 'max:50'],
            'odometer' => ['required', 'integer', 'min:0'],
            'driver_name' => ['nullable', 'string', 'max:255'],
            'driver_id' => ['nullable', 'integer', 'exists:operators,id'],
            'date' => ['required', 'date'],
            'route_id' => ['nullable', 'integer', 'exists:routes,id'],
            'ticket_photo' => ['nullable', 'image', 'max:5120'],
            'folio' => ['nullable', 'string', 'max:100'],
            'fuel_station_id' => ['nullable', 'integer', 'exists:fuel_stations,id'],
            'fuel_card_id' => ['nullable', 'integer', 'exists:fuel_cards,id'],
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
