<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VacationRequest;

class VacationRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
        /**
     * Determine if the given vacation request can be created by the user.
     */
    public function create(User $user)
    {
        return $user->isUser();
    }

    /**
     * Determine if the given vacation request can be updated by the user.
     */
    public function update(User $user, VacationRequest $vacationRequest)
    {
        return ($user->id === $vacationRequest->user_id && $user->isUser());
    }

    /**
     * Determine if the given vacation request can be deleted by the user.
     */
    public function delete(User $user, VacationRequest $vacationRequest)
    {
        return ($user->id === $vacationRequest->user_id && $user->isUser());
    }

    /**
     * Determine if the given vacation request can be approved by the manager.
     */
    public function approve(User $user, VacationRequest $vacationRequest)
    {
        return $user->isManager() && $user->team->first()->id === $vacationRequest->team_id;
    }

    /**
     * Determine if the given vacation request can be viewed by the user.
     */
    public function view(User $user, VacationRequest $vacationRequest)
    {
        return $user->id === $vacationRequest->user_id || $user->isManager() || $user->isAdmin();
    }

    public function viewHistory(User $user, VacationRequest $vacationRequest)
    {
        // Only the user who created the vacation request can view their own history
        return $user->id === $vacationRequest->user_id;
    }

    public function viewAny(User $user)
    {
        // Only allow admins to view any vacation requests
        return $user->isAdmin();
    }

}
