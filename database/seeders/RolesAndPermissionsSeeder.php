<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'secretary']);
        Role::create(['name' => 'user']);


        // Create permissions
        Permission::create(['name' => 'create patient']);
        Permission::create(['name' => 'edit patient']);
        Permission::create(['name' => 'delete patient']);
        Permission::create(['name' => 'get patient']);

        // Assign permissions to roles
        Role::findByName('admin')->givePermissionTo(['create patient', 'edit patient', 'delete patient', 'get patient']);
        Role::findByName('secretary')->givePermissionTo(['create patient', 'edit patient', 'delete patient']);

    }
}
