<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, User $target)
    {
        return $user->can('edit') && ($target->role->id === 0 || $user->role->subordinates()->contains($target->role));
    }

    public function delete(User $user, User $target)
    {
        return $user->can('delete') && ($target->role->id === 0 || $user->role->subordinates()->contains($target->role));
    }
}
