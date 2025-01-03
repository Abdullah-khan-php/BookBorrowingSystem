<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Create permissions
        $permissions = [
            'view books',
            'borrow books',
            'export books',
            'return books',
            'add books',
            'edit books',
            'delete books',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo([ 'view books','export books', 'add books','edit books','delete books']);
        $user->givePermissionTo(['view books', 'borrow books', 'export books','return books']);
    }
}

