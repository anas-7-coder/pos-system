<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('tax_classes.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:tax_classes,code'],

            'description' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
