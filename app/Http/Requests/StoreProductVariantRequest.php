<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// use Illuminate\Validation\Rule;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('product_variants.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:150'],

            'sku' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:product_variants,sku'],

            'cost_price' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],

            'selling_price' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],

            'attributes' => ['nullable', 'array'],

            'is_default' => ['required', 'boolean'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
