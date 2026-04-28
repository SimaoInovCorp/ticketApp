<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nif'            => $this->nif,
            'name'           => $this->name,
            'phone'          => $this->phone,
            'mobile'         => $this->mobile,
            'website'        => $this->website,
            'email'          => $this->email,
            'internal_notes' => $this->when($request->user()?->isOperator(), $this->internal_notes),
            'contacts_count' => $this->whenCounted('contacts'),
            'contacts'       => $this->whenLoaded('contacts', fn () => $this->contacts->map(fn ($c) => [
                'id'    => $c->id,
                'name'  => $c->name,
                'email' => $c->email,
                'phone' => $c->phone,
                'role'  => $c->relationLoaded('role') && $c->role
                    ? ['id' => $c->role->id, 'name' => $c->role->name]
                    : null,
            ])),
        ];
    }
}
