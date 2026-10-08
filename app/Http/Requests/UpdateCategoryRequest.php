<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('categories.update');
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('categories', 'code')->ignore($category->id)],

            'description' => ['nullable', 'string', 'max:500'],

            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category->id])],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
