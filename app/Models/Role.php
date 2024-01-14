<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;

class Role extends SpatieRole
{
    // Define your roles and permissions here
    public static function defineRoles()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $lawyerRole = Role::create(['name' => 'lawyer']);
        $paralegalRole = Role::create(['name' => 'paralegal']);
        $clientRole = Role::create(['name' => 'client']);
        $judgeRole = Role::create(['name' => 'judge']);

        // Define permissions
        $adminPermissions = [
            // Define admin permissions here
        ];
        $lawyerPermissions = [
            // Define lawyer permissions here
        ];
        $paralegalPermissions = [
            // Define paralegal permissions here
        ];
        $clientPermissions = [
            // Define client permissions here
        ];
        $judgePermissions = [
            // Define judge permissions here
        ];

        // Assign permissions to roles
        $adminRole->givePermissionTo($adminPermissions);
        $lawyerRole->givePermissionTo($lawyerPermissions);
        $paralegalRole->givePermissionTo($paralegalPermissions);
        $clientRole->givePermissionTo($clientPermissions);
        $judgeRole->givePermissionTo($judgePermissions);
    }
}
