<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('customerGroup'));
    }

    public function rules(): array
    {
        $customerGroup = $this->route('customerGroup');

        return [
            'name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('customer_groups', 'name')->ignore($customerGroup->id)],

            'code' => ['required', 'string', 'max:50', Rule::unique('customer_groups', 'code')->ignore($customerGroup->id)],

            'description' => ['nullable', 'string', 'max:1000'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
