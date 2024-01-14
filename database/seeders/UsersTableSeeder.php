<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Administrator', 'Lawyer', 'Paralegal', 'Client', 'Judge'];

        foreach ($roles as $role) {
            for ($i = 1; $i <= 2; $i++) {
                // Create user
                $user = User::create([
                    'name' => "{$role} Name{$i}",
                    'email' => strtolower("{$role}{$i}@example.com"),
                    'password' => Hash::make('welcome'),
                ]);

                // Assign role to the user
                $user->assignRole($role);
            }
        }
    }
}
