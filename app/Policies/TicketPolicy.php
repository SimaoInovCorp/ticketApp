<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isOperator()) {
            return $ticket->inbox->operators()->where('users.id', $user->id)->exists();
        }

        $contact = $user->contact()->with('entities')->first();
        if (!$contact || !$ticket->entity_id) {
            return false;
        }

        return $contact->entities->contains('id', $ticket->entity_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isOperator() &&
            $ticket->inbox->operators()->where('users.id', $user->id)->exists();
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->isOperator() &&
            $ticket->inbox->operators()->where('users.id', $user->id)->exists();
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $this->update($user, $ticket);
    }
}
