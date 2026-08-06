<?php

namespace App\Domains\Incident\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Domains\Incident\Models\Incident::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['required', 'exists:operators,id'],
            'driver_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'severity' => ['required', 'string', 'in:Baja,Media,Alta'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'string', 'max:10'],
            'type' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'involves_third_party' => ['boolean'],
            'third_party_data' => ['nullable', 'json'],
            'photo' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Reportada,Atendida,Resuelta,Cerrada'],
        ];
    }
}
