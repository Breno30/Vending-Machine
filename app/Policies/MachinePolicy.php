<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;

class MachinePolicy
{
    // use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Machine $machine): bool
    {
        return $user->id === $machine->owner_id;
    }
}
