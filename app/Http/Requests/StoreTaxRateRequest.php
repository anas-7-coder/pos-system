<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// use Illuminate\Validation\Rule;

class StoreTaxRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('tax_rates.create');
    }

    public function rules(): array
    {
        return [
            'tax_class_id' => ['required', 'exists:tax_classes,id'],

            'name' => ['required', 'string', 'min:2', 'max:100'],

            'rate' => ['required', 'numeric', 'min:0', 'max:100'],

            'effective_from' => ['required', 'date'],

            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],

            'status' => ['required', 'in:Active,Inactive'],

            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
