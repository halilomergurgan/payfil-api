<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // permissions
        Permission::create(['name' => 'view transactions']);
        Permission::create(['name' => 'manage payments']);

        // role attach
        $admin->givePermissionTo('view transactions');
        $admin->givePermissionTo('manage payments');

        $user->givePermissionTo('view transactions');
    }
}
