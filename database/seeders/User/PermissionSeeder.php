<?php

namespace Database\Seeders\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $guard = config('auth.defaults.guard');


        Schema::disableForeignKeyConstraints();

        Role::truncate();
        Permission::truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        $roles = ['superadmin', 'admin', 'marketing', 'sales'];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => $guard]);
        }

        /** ADMIN */
        $adminAccessPermission = Permission::create(['name' => 'admin access', 'guard_name' => $guard]);
        $adminRole = Role::whereName('admin')->first();
        $adminRole->givePermissionTo($adminAccessPermission);

        /** MANAGER */
        $marketingRole = Role::whereName('marketing')->first();
        $marketingPermissions = [
            'campaign read',
            'campaign create',
            'campaign update',
            'campaign delete',
        ];

        foreach ($marketingPermissions as $name) {
            $permission = Permission::create(['name' => $name, 'guard_name' => $guard]);
            $marketingRole->givePermissionTo($permission);
        }

        Schema::enableForeignKeyConstraints();
    }
}
