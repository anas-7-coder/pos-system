<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('brands.update');
    }

    public function rules(): array
    {
        $brand = $this->route('brand');

        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('brands', 'code')->ignore($brand->id)],

            'description' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
