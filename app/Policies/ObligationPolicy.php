<?php

namespace App\Policies;

use App\Models\Obligation;
use App\Models\User;

class ObligationPolicy
{
    public function update(User $user, Obligation $obligation): bool
    {
        return $user->id === $obligation->user_id;
    }

    public function delete(User $user, Obligation $obligation): bool
    {
        return $user->id === $obligation->user_id;
    }
}
