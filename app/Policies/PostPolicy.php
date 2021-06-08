<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Post;

class PostPolicy
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

    public function edit(User $user, Post $post)
    {
        return $user->can('edit') && ($post->user->id === 0 || $post->user->role->id === 0 || $user->role->subordinates()->contains($post->user->role));
    }

    public function delete(User $user, Post $post)
    {
        return $user->can('delete') && ($post->user->id === 0 || $post->user->role->id === 0 || $user->role->subordinates()->contains($post->user->role));
    }
}
