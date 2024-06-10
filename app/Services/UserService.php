<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class UserService
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        // $defaultRole = config('auth.default_role'); // Use configuration for role name
        $defaultRole = 'user';
        try { // Attempt to create the role if it doesn't exist
            if (!Role::where('name', $defaultRole)->exists()) {
                Role::create([
                    'name' => $defaultRole,
                ]);
            }
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->save();
            $user->refresh()->roles()->attach(Role::where('name', $defaultRole)->firstOrFail());
            return $user;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(User $user, array $data): User
    {
        try {
            $user->update($data);
            if (isset($data['role_id']) && count($data['role_id'])) {
                $user->roles()->sync($data['role_id']);
            }
            return $user;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
