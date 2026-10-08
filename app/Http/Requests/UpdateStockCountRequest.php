<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStockCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('stockCount'));
    }

    public function rules(): array
    {
        $stockCount = $this->route('stockCount');

        return [
            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'count_number' => ['required', 'string', 'max:100', Rule::unique('stock_counts', 'count_number')->ignore($stockCount->id)],

            'count_date' => ['required', 'date'],

            'notes' => ['nullable', 'string', 'max:2000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => ['required', 'exists:products,id'],

            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'items.*.counted_quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
