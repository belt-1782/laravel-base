<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-update',
            'user-delete',
            'user-show'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        Role::findByName('admin')->givePermissionTo($permissions);

        $permissionMember = Permission::whereIn('name', [
            'user-list',
            'user-update',
            'user-show'
        ])->get();
        Role::findByName('member')->givePermissionTo($permissionMember);
    }
}
