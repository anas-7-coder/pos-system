<?php

namespace App\Http\Requests;

use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Sale::class);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'exists:customers,id'],

            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'register_session_id' => ['required', 'exists:register_sessions,id'],

            'invoice_number' => ['required', 'string', 'max:100', 'unique:sales,invoice_number'],

            'sale_date' => ['required', 'date'],

            'notes' => ['nullable', 'string', 'max:2000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => ['required', 'exists:products,id'],

            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'items.*.quantity' => ['required', 'numeric', 'gt:0'],

            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
