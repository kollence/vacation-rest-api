<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TeamService
{
    public function createTeam(array $data): Team
    {
        $validatedData = $data;   // Create the team
        return DB::transaction(function () use ($validatedData) {
            // Check if the users have the required roles
            $managerIds = $validatedData['managers'];
            $userIds = $validatedData['regular_users'];

            foreach ($managerIds as $managerId) {
                $manager = User::findOrFail($managerId);
                if (!$manager->roles()->where('name', 'manager')->exists()) {
                    throw ValidationException::withMessages(['managers' => 'User ID ' . $managerId . ' does not have the manager role.']);
                }
            }
            // Logic to add only users with role User to regular_users
            $team = Team::create([
                'name' => $validatedData['name']
            ]);
            // if user ids are set
            if(isset($userIds) && $userIds != null){
                foreach ($userIds as $userId) {
                    $user = User::findOrFail($userId);
                    if (!$user->roles()->where('name', 'user')->exists()) {
                        throw ValidationException::withMessages(['regular_users' => 'User ID ' . $userId . ' does not have the user role.']);
                    }
                }
                // Attach regular users to the team with 'user' role
                $team->users()->attach($validatedData['regular_users'], ['role' => 'user']);
            }
            // Attach managers to the team with 'manager' role
            $team->users()->attach($validatedData['managers'], ['role' => 'manager']);

            return $team;
        });
        
    }

    public function updateTeam(Team $team, array $data): Team
    {
        return DB::transaction(function () use ($data, $team) {
            if (isset($data['name'])) {
                $team->update(['name' => $data['name']]);
            }

            // Handle managers update if provided
            if (isset($data['managers'])) {
                $managerIds = $data['managers'];
                foreach ($managerIds as $managerId) {
                    $manager = User::findOrFail($managerId);
                    if (!$manager->roles()->where('name', 'manager')->exists()) {
                        throw ValidationException::withMessages(['managers' => 'User ID ' . $managerId . ' does not have the manager role.']);
                    }
                }

                $managerIdsWithRole = [];
                foreach ($managerIds as $managerId) {
                    $managerIdsWithRole[$managerId] = ['role' => 'manager'];
                }
                $team->users()->syncWithoutDetaching($managerIdsWithRole);
            }

            // Handle regular users update if provided
            if (isset($data['regular_users'])) {
                $userIds = $data['regular_users'];
                foreach ($userIds as $userId) {
                    $user = User::findOrFail($userId);
                    if (!$user->roles()->where('name', 'user')->exists()) {
                        throw ValidationException::withMessages(['regular_users' => 'User ID ' . $userId . ' does not have the user role.']);
                    }
                }

                $userIdsWithRole = [];
                foreach ($userIds as $userId) {
                    $userIdsWithRole[$userId] = ['role' => 'user'];
                }
                $team->users()->syncWithoutDetaching($userIdsWithRole);
            }
            
            return $team;
        });
    }

    public function deleteTeam(Team $team): bool
    {
        return $team->delete();
    }

    public function removeTeamUser(Team $team, array $data)
    {
        return DB::transaction(function () use ($data, $team) {

            if (isset($data['managers'])) {
                // Ensure there will be at least one manager left after deletion
                $currentManagerCount = $team->users()->wherePivot('role', 'manager')->count();
                $managerCountAfterDeletion = $currentManagerCount - count($data['managers']);

                if ($managerCountAfterDeletion < 1) {
                    throw ValidationException::withMessages(['managers' => 'You must leave at least one manager in the team.']);
                }
                // Detach the specified users and managers
                $team->users()->detach($data['managers']);
            }

            if (isset($data['regular_users'])) {
                $team->users()->detach($data['regular_users']);
            }

            return $team;
        });
    }
}
