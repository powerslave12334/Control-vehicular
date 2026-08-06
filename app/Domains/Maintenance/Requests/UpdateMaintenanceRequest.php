<?php

namespace App\Domains\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('maintenance')) ?? false;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'type' => ['required', 'string', 'in:Preventivo,Correctivo'],
            'description' => ['required', 'string', 'max:1000'],
            'cost' => ['required', 'numeric', 'min:0'],
            'workshop' => ['required', 'string', 'max:255'],
            'odometer' => ['nullable', 'integer', 'min:0'],
            'date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:255'],
            'scheduled_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:scheduled,in_progress,completed,cancelled'],
        ];
    }
}
