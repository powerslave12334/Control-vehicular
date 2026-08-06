<?php

namespace App\Domains\Route\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('route')) ?? false;
    }

    public function rules(): array
    {
        return [
            'driver_id' => ['required', 'exists:users,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'planned_km' => ['required', 'numeric', 'min:1'],
            'date' => ['required', 'date'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'estimated_duration' => ['nullable', 'numeric', 'min:0'],
            'actual_km' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:Programada,Asignada,En tránsito,Instalando,Completada,Cancelada'],
        ];
    }
}
