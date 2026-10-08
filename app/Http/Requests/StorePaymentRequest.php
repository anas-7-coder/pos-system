<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', Rule::in(['cash', 'card', 'bank_transfer', 'mobile_wallet'])],

            'amount' => ['required', 'numeric', 'min:0.01'],

            'received_amount' => ['nullable', 'numeric', 'min:0'],

            'reference' => ['nullable', 'string', 'max:255', 'required_unless:payment_method,cash'],
        ];
    }

    public function messages(): array
    {
        return ['reference.required_unless' => 'A payment reference is required for non-cash payments.'];
    }
}
