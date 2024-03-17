<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Food;

class FoodPolicy
{
    /**
     * Create a new policy instance.
     */
    public function verify(User $user, Food $searchingFood)
    {

        return $user->uuid === $searchingFood->donator_id;
    }
}