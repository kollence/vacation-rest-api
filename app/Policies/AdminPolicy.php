<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function before(User $user)
    {
        // This will grant all abilities to the admin user
        if ($user->isAdmin()) {
            return true;
        }
    }
}
