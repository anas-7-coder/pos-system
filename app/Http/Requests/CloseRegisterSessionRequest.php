<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseRegisterSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasPermission('register_sessions.close');
    }

    public function rules(): array
    {
        return [
            'actual_cash' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ];
    }
}
