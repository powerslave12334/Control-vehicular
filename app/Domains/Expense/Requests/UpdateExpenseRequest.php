<?php

namespace App\Domains\Expense\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('expense')) ?? false;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'operator_id' => ['nullable', 'integer', 'exists:operators,id'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'folio' => ['nullable', 'string', 'max:50'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
