<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('units.update');
    }

    public function rules(): array
    {
        $unit = $this->route('unit');

        return [
            'name' => ['required', 'string', 'min:1', 'max:100'],

            'short_name' => ['required', 'string', 'max:20'],

            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('units', 'code')->ignore($unit->id)],

            'allows_decimal' => ['required', 'boolean'],

            'precision' => ['required', 'numeric', 'gt:0', 'max:999999.9999'],

            'description' => ['nullable', 'string', 'max:500'],

            'status' => ['required', 'in:Active,Inactive'],
        ];
    }
}
