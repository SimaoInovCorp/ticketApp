<?php

namespace App\Policies;

use App\Models\Entity;
use App\Models\User;

class EntityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isOperator();
    }

    public function view(User $user, Entity $entity): bool
    {
        return $user->isOperator();
    }

    public function create(User $user): bool
    {
        return $user->isOperator();
    }

    public function update(User $user, Entity $entity): bool
    {
        return $user->isOperator();
    }

    public function delete(User $user, Entity $entity): bool
    {
        return $user->isOperator();
    }
}
