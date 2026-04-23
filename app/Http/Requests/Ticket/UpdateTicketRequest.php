<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOperator();
    }

    public function rules(): array
    {
        return [
            'subject'            => ['sometimes', 'string', 'max:255'],
            'ticket_type_id'     => ['nullable', 'integer', 'exists:ticket_types,id'],
            'entity_id'          => ['nullable', 'integer', 'exists:entities,id'],
            'knowledge_emails'   => ['nullable', 'array', 'max:5'],
            'knowledge_emails.*' => ['email', 'max:255'],
        ];
    }
}
