<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'register_session_id' => ['required', 'integer', 'exists:register_sessions,id'],

            'type' => ['required', Rule::in(['cash_in', 'cash_out'])],

            'amount' => ['required', 'numeric', 'min:0.01'],

            'reference' => ['nullable', 'string', 'max:255'],

            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
