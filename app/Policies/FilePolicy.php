<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\File;

class FilePolicy
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

    public function edit(User $user, File $file)
    {
        return $user->can('edit') && $file->user->id === $user->id;
    }

    public function delete(User $user, File $file)
    {
        return $user->can('delete') && $file->user->id === $user->id;
    }
}
