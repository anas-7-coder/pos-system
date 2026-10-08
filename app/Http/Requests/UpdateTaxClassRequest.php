<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaxClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('tax_classes.update');
    }

    public function rules(): array
    {
        $taxClass = $this->route('tax_class');

        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('tax_classes', 'code')->ignore($taxClass->id)],

            'description' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
