<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('categories.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:categories,code'],

            'description' => ['nullable', 'string', 'max:500'],

            'parent_id' => ['nullable', 'exists:categories,id'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
