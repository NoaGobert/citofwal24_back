<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function verify(User $user, User $searchingUser)
    {

        return $user->uuid === $searchingUser->uuid;
    }
}