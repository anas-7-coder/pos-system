<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoodsReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('goodsReceipt'));
    }

    public function rules(): array
    {
        return [
            'receipt_number' => ['required', 'string', 'max:100', Rule::unique('goods_receipts', 'receipt_number')->ignore($this->route('goodsReceipt')->id)],

            'receipt_date' => ['required', 'date'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.purchase_order_item_id' => ['required', 'exists:purchase_order_items,id', 'distinct'],

            'items.*.received_quantity' => ['required', 'numeric', 'gt:0'],

            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
