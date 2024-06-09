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

        $user = User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@mail.com',
        ]);
        $roleId = Role::where('name', $roles[0])->first()->id;
        $user->roles()->attach($roleId);
        $user->createToken('user-token')->plainTextToken;
        $user2 = User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@mail.com',
        ]);
        $roleId = Role::where('name', $roles[0])->first()->id;
        $user2->roles()->attach($roleId);
        $user2->createToken('user-token')->plainTextToken;
        $user3 = User::factory()->create([
            'name' => 'User3',
            'email' => 'user3@mail.com',
        ]);
        $roleId = Role::where('name', $roles[0])->first()->id;
        $user3->roles()->attach($roleId);
        $user3->createToken('user-token')->plainTextToken;

        $manager = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@mail.com',
        ]);
        $roleId = Role::where('name', $roles[1])->first()->id;
        $manager->roles()->attach($roleId);
        $manager->createToken('manager-token')->plainTextToken;

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);
        $roleId = Role::where('name', $roles[2])->first()->id;
        $admin->roles()->attach($roleId);
        $admin->createToken('admin-token')->plainTextToken;
    }
}
