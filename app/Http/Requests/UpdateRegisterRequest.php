<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->hasPermission('registers.update');
    }

    public function rules(): array
    {
        $register = $this->route('register');

        return [
            'branch_id' => ['required', 'exists:branches,id'],

            'name' => ['required', 'string', 'min:2', 'max:100'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('registers', 'code')
                    ->where(fn ($query) => $query->where('branch_id', $this->branch_id))
                    ->ignore($register->id),
            ],

            'device_identifier' => ['nullable', 'string', 'max:150'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
