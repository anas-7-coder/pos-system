<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('productPrice'));
    }

    public function rules(): array
    {
        return [
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'branch_id' => ['nullable', 'exists:branches,id'],

            'price_type' => ['required', Rule::in(['retail', 'wholesale', 'special'])],

            'price' => ['required', 'numeric', 'min:0'],

            'effective_from' => ['required', 'date'],

            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],

            'status' => ['required', Rule::in(['Active', 'Inactive'])],

            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
