<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class MenuItemPolicy
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

    public function browse(User $user)
    {
        return $user->can('browse') && $user->role->rank == 1;
    }

    public function add(User $user)
    {
        return $user->can('add') && $user->role->rank == 1;
    }

    public function edit(User $user)
    {
        return $user->can('edit') && $user->role->rank == 1;
    }

    public function delete(User $user)
    {
        return $user->can('delete') && $user->role->rank == 1;
    }
}
