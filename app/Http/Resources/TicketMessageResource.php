<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'body'        => $this->body,
            'is_internal' => $this->is_internal,
            'author'      => $this->whenLoaded('author', fn () => $this->author ? [
                'id'   => $this->author->id,
                'name' => $this->author->name,
                'role' => $this->author->role,
            ] : null),
            'attachments' => TicketAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
