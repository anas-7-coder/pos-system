<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('brands.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:brands,code'],

            'description' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
