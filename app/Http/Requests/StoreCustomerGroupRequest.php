<?php

namespace App\Http\Requests;

use App\Models\CustomerGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CustomerGroup::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:customer_groups,name'],

            'code' => ['required', 'string', 'max:50', 'unique:customer_groups,code'],

            'description' => ['nullable', 'string', 'max:1000'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
