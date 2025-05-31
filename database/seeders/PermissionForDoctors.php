<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionForDoctors extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // permission for doctors ( libyan - foreign  - visitors - palestinian )

        $permissions = [
        //    'اطباء اجانب' =>  'doctor-foreign',
        //    'اطباء زوار' =>  'doctor-visitors',
            // 'اطباء فلسطينيين' =>  'doctor-palestinian',
        ];

        foreach ($permissions as $key=>$permission) {

            // create permission
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ], [
                'name' => $permission,
                'guard_name' => 'web',
                'display_name' => $key,
            ]);

            // attach permission to role
            $role = \Spatie\Permission\Models\Role::findById(1);
            $role->givePermissionTo($permission);
            
        }
    }
}
