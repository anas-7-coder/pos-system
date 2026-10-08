<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->hasPermission('branches.create');
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],

            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:branches,code,NULL,id,company_id,'.$this->company_id],

            'email' => ['nullable', 'email', 'max:150'],

            'phone' => ['nullable', 'string', 'max:20'],

            'address' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
