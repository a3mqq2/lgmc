<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DoctorMailPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $role = Role::findById(1);
      
        // new permission
        $permission = 'doctor-mail';

        // display name
        $displayName = 'اوراق الخارج';

        // create permission
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ], [
            'name' => $permission,
            'guard_name' => 'web',
            'display_name' => $displayName,
        ]);

        // attach permission to role
        $role->givePermissionTo($permission);
    }
}
