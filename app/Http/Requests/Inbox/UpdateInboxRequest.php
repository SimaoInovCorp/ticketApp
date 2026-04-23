<?php

namespace App\Http\Requests\Inbox;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInboxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOperator();
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'regex:/^[A-Za-zÀ-ÿ0-9 _-]+$/u'],
            'slug'        => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('inboxes', 'slug')->ignore($this->route('inbox'))],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
