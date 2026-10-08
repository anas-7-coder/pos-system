<?php

namespace App\Http\Requests;

use App\Models\StockCount;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', StockCount::class);
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'count_number' => ['required', 'string', 'max:100', 'unique:stock_counts,count_number'],

            'count_date' => ['required', 'date'],

            'notes' => ['nullable', 'string', 'max:2000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => ['required', 'exists:products,id'],

            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'items.*.counted_quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
