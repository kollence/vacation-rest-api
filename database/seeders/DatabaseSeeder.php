<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate 3 type of user
        $roles = ['user', 'manager', 'admin'];

        // Create roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
        // Create users
        foreach(range(1, 5) as $user) {
            $user = User::factory()->create([
                'name' => 'User'.$user,
                'email' => 'user'.$user.'@mail.com',
            ]);
            $roleId = Role::where('name', $roles[0])->first()->id;
            $user->roles()->attach($roleId);
            $user->createToken('user-token')->plainTextToken;
        }
        // Create managers
        foreach(range(1, 3) as $manager) {
            $manager = User::factory()->create([
                'name' => 'Manager'.$manager,
                'email' => 'manager'.$manager.'@mail.com',
            ]);
            $roleId = Role::where('name', $roles[1])->first()->id;
            $manager->roles()->attach($roleId);
            $manager->createToken('manager-token')->plainTextToken;
        }

        // Create admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);
        $roleId = Role::where('name', $roles[2])->first()->id;
        $admin->roles()->attach($roleId);
        $admin->createToken('admin-token')->plainTextToken;
    }
}
