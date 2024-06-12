<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{

    public function viewAny(User $user)
    {
        // Allow only admin users to view all teams
        return $user->isAdmin();
    }
    public function view(User $user, Team $team)
    {
        // Managers can only view their own team
        return $user->isManager() && $user->team->first()->id == $team->id || $user->isUser() && $user->team->first()->id === $team->id || $user->isAdmin();
    }

    // Other actions for admins
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Team $team)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Team $team)
    {
        return $user->isAdmin();
    }
}
