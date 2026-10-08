<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'register_session_id' => ['nullable', 'integer', 'exists:register_sessions,id', 'required_if:payment_method,cash'],

            'payment_method' => ['required', Rule::in(['cash', 'card', 'bank_transfer', 'mobile_wallet'])],

            'amount' => ['required', 'numeric', 'gt:0'],

            'received_amount' => ['nullable', 'numeric', 'min:0', 'required_if:payment_method,cash'],

            'reference' => ['nullable', 'string', 'max:255', 'required_unless:payment_method,cash'],

            'allocations' => ['nullable', 'array'],

            'allocations.*.sale_id' => ['required', 'integer', 'exists:sales,id'],

            'allocations.*.amount' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
