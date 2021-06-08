<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Role;

class RolePolicy
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

    public function edit(User $user, Role $role)
    {
        return $user->can('edit') && $user->role->subordinates()->contains($role);
    }

    public function delete(User $user, Role $role)
    {
        return $user->can('delete') && $user->role->subordinates()->contains($role);
    }

    public function attach(User $user, Role $role)
    {
        return $user->can('edit') && $user->role->subordinates()->contains($role);
    }
}
