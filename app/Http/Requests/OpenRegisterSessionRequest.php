<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenRegisterSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('register_sessions.open');
    }

    public function rules(): array
    {
        return [
            'register_id' => ['required', 'exists:registers,id'],

            'opening_cash' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ];
    }
}
