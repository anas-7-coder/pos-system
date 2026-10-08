<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('warehouses.create');
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],

            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash'],

            'address' => ['nullable', 'string', 'max:500'],

            'is_default' => ['sometimes', 'boolean'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
