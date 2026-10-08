<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('companies.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:companies,code'],

            'email' => ['nullable', 'email', 'max:150'],

            'phone' => ['nullable', 'string', 'max:20'],

            'address' => ['nullable', 'string', 'max:500'],

            'tax_number' => ['nullable', 'string', 'max:100'],

            'currency' => ['required', 'string', 'size:3'],

            'timezone' => ['required', 'string', 'timezone'],

            'status' => ['required', 'in:Active, Inactive'],
        ];
    }
}
