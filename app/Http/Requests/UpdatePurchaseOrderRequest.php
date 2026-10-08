<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('purchaseOrder'));
    }

    public function rules(): array
    {
        $purchaseOrder = $this->route('purchaseOrder');

        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],

            'branch_id' => ['required', 'exists:branches,id'],

            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'po_number' => ['required', 'string', 'max:100', Rule::unique('purchase_orders', 'po_number')->ignore($purchaseOrder->id)],

            'order_date' => ['required', 'date'],

            'expected_date' => ['nullable', 'date', 'after_or_equal:order_date'],

            // 'status'                     => ['required', Rule::in(['draft', 'submitted', 'approved', 'cancelled', 'closed'])],

            'notes' => ['nullable', 'string', 'max:2000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => ['required', 'exists:products,id'],

            'items.*.product_variant_id' => ['nullable', 'exists:product_variants,id'],

            'items.*.quantity' => ['required', 'numeric', 'gt:0'],

            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],

            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],

            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
