<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('products.create');
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],

            'brand_id' => ['nullable', 'exists:brands,id'],

            'unit_id' => ['required', 'exists:units,id'],

            'tax_class_id' => ['nullable', 'exists:tax_classes,id'],

            'name' => ['required', 'string', 'min:2', 'max:200'],

            'sku' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:products,sku'],

            'description' => ['nullable', 'string', 'max:1000'],

            'product_type' => ['required', 'in:stockable,service'],

            'cost_price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],

            'selling_price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],

            'track_inventory' => ['required', 'boolean'],

            'allow_negative_stock' => ['nullable', 'boolean'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
