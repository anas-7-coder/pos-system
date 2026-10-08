<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('warehouses.update');
    }

    public function rules(): array
    {
        $warehouse = $this->route('warehouse');

        return [
            'branch_id' => ['required', 'exists:branches,id'],

            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('warehouses', 'code')
                    ->where(
                        fn ($query) => $query->where('branch_id', $this->branch_id)
                    )
                    ->ignore($warehouse->id),
            ],

            'address' => ['nullable', 'string', 'max:500'],

            'is_default' => ['sometimes', 'boolean'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
