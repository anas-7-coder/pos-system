<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],

            'items.*.sale_item_id' => ['required', 'integer', 'exists:sale_items,id'],

            'items.*.quantity' => ['required', 'numeric', 'gt:0'],

            'items.*.reason' => ['nullable', 'string', 'max:255'],

            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
