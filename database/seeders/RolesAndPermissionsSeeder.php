<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Permissions
        Permission::firstOrCreate(['name' => 'view transactions']);
        Permission::firstOrCreate(['name' => 'manage payments']);

        // Role attach
        if (!$admin->hasPermissionTo('view transactions')) {
            $admin->givePermissionTo('view transactions');
        }

        if (!$admin->hasPermissionTo('manage payments')) {
            $admin->givePermissionTo('manage payments');
        }

        if (!$user->hasPermissionTo('view transactions')) {
            $user->givePermissionTo('view transactions');
        }
    }
}

