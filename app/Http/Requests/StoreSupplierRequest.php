<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Supplier::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'unique:suppliers,code'],

            'phone' => ['nullable', 'string', 'max:30'],

            'email' => ['nullable', 'email', 'max:150'],

            'address' => ['nullable', 'string', 'max:1000'],

            'tax_number' => ['nullable', 'string', 'max:100'],

            'opening_balance' => ['required', 'numeric', 'min:0'],

            'credit_limit' => ['required', 'numeric', 'min:0'],

            'status' => ['required', Rule::in(['Active', 'Inactive'])],

            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
