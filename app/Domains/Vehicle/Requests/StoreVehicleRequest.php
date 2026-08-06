<?php

namespace App\Domains\Vehicle\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Domains\Vehicle\Models\Vehicle::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:' . (now()->year + 1)],
            'plate' => ['required', 'string', 'max:20', 'unique:vehicles,plate'],
            'fuel_type' => ['required', 'string', 'max:50'],
            'cargo_capacity' => ['required', 'string', 'max:50'],
            'tank_capacity' => ['required', 'numeric', 'min:1'],
            'gps_installed' => ['boolean'],
            'vin' => ['required', 'string', 'max:50', 'unique:vehicles,vin'],
            'color' => ['nullable', 'string', 'max:50'],
            'engine' => ['nullable', 'string', 'max:100'],
            'acquisition_date' => ['nullable', 'date'],
            'acquisition_cost' => ['nullable', 'numeric', 'min:0'],
            'vehicle_type' => ['nullable', 'string'],
            'assigned_driver_id' => ['nullable', 'integer', 'exists:users,id'],
            'current_odometer' => ['required', 'integer', 'min:0'],
            'authorized_fuel' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
