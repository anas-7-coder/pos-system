<?php

namespace App\Http\Requests;

use App\Models\StockTransfer;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', StockTransfer::class);
    }

    public function rules(): array
    {
        return [
            'from_warehouse_id' => ['required', 'exists:warehouses,id'],

            'to_warehouse_id' => ['required', 'exists:warehouses,id', 'different:from_warehouse_id'],

            'transfer_number' => ['required', 'string', 'max:100', 'unique:stock_transfers,transfer_number'],

            'transfer_date' => ['required', 'date'],

            'notes' => ['nullable', 'string', 'max:2000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => ['required', 'exists:products,id'],

            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
