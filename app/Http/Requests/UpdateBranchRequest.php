<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('branches.update');
    }

    public function rules(): array
    {
        $branch = $this->route('branch');

        return [
            'company_id' => ['required', 'exists:companies,id'],

            'name' => ['required', 'string', 'min:2', 'max:150'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('branches', 'code')
                    ->where(
                        fn ($query) => $query->where('company_id', $this->company_id)
                    )
                    ->ignore($branch->id),
            ],

            'email' => ['nullable', 'email', 'max:150'],

            'phone' => ['nullable', 'string', 'max:20'],

            'address' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
