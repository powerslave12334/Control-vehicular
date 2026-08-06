<?php

namespace App\Domains\Operator\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('operator')) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'license_type' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:Activo,Inactivo,Suspendido'],
            'document_type' => ['nullable', 'string', 'max:50'],
            'document_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'emergency_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
