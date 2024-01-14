<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CreateUsersForRoles extends Command
{
    protected $signature = 'users:create-for-roles';
    protected $description = 'Create a user for each type of role';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $roleEmails = [
            'administrator' => 'admin@cms.com',
            'lawyer' => 'lawyer@cms.com',
            'paralegal' => 'paralegal@cms.com',
            'client' => 'client@cms.com',
            'judge' => 'judge@cms.com',
        ];

        foreach ($roleEmails as $roleName => $email) {
            $name = ucfirst($roleName);
            $password = bcrypt('password'); // Set a default password

            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                ]);

                $user->syncRoles([$role]);
            }
        }

        $this->info('Users created for each role.');
    }
}
