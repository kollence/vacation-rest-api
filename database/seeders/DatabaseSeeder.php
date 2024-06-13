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
        // User::factory(10)->create();
        $roles = ['user', 'manager', 'admin'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
        foreach(range(1, 5) as $user) {
            $user = User::factory()->create([
                'name' => 'User'.$user,
                'email' => 'user'.$user.'@mail.com',
            ]);
            $roleId = Role::where('name', $roles[0])->first()->id;
            $user->roles()->attach($roleId);
            $user->createToken('user-token')->plainTextToken;
        }
        foreach(range(1, 3) as $manager) {
            $manager = User::factory()->create([
                'name' => 'Manager'.$manager,
                'email' => 'Manager'.$manager.'@mail.com',
            ]);
            $roleId = Role::where('name', $roles[1])->first()->id;
            $manager->roles()->attach($roleId);
            $manager->createToken('manager-token')->plainTextToken;
        }

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);
        $roleId = Role::where('name', $roles[2])->first()->id;
        $admin->roles()->attach($roleId);
        $admin->createToken('admin-token')->plainTextToken;
    }
}
