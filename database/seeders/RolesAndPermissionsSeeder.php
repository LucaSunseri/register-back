<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'export']);
        Permission::create(['name' => 'activity-management']);
        Permission::create(['name' => 'role-management']);
        Permission::create(['name' => 'permission-management']);
        Permission::create(['name' => 'user-management']);
        Permission::create(['name' => 'show-all-attendances']);

        $role = Role::create(['name' => 'developer']);

        $role = Role::create(['name' => 'tutor']);
        $role->givePermissionTo('export');
        $role->givePermissionTo('activity-management');
        $role->givePermissionTo('show-all-attendances');

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
