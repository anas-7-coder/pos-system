<?php

namespace App\Http\Requests;

use App\Models\StockMovement;
use Illuminate\Foundation\Http\FormRequest;

class StoreReorderLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('adjust', StockMovement::class);
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'product_id' => ['required', 'exists:products,id'],

            'product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'reorder_level' => ['required', 'numeric', 'min:0'],
        ];
    }
}
