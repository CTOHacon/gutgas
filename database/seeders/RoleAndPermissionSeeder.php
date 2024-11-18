<?php

namespace Database\Seeders;

use App\Providers\PermissionsServiceProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (PermissionsServiceProvider::ROLES as $role) {
            Role::create(['name' => $role]);
        }
        foreach (PermissionsServiceProvider::PERMISSIONS as $permission) {
            Permission::create(['name' => $permission]);
        }
        foreach (PermissionsServiceProvider::ROLE_PERMISSIONS as $role => $permissions) {
            $role = Role::findByName($role);
            $role->givePermissionTo($permissions);
        }
    }
}
