<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'register_session_id' => ['required', 'integer', 'exists:register_sessions,id'],

            'payment_method' => ['required', Rule::in(['cash', 'card', 'bank_transfer', 'mobile_wallet'])],

            'reference' => ['nullable', 'string', 'max:255', 'required_unless:payment_method,cash'],
        ];
    }
}
