<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        /**
         * Define roles with English 'name' and Arabic 'display_name'.
         */
        $rolesData = [
            [
                'name'         => 'general_admin',
                'display_name' => 'إدارة عامة',
            ],
            [
                'name'         => 'branch_operations',
                'display_name' => 'عمليات الفرع',
            ],
        ];


        $generalAdministrationPermissions = [
            'manage-staff'              => 'الموظفين',
            'manage-medical-facilities'  => 'المنشات  الطبيه',
            // DOCTORS PERMISSONS
            'doctor-foreign'         => 'اطباء اجانب',
            'doctor-palestinian'         => 'اطباء فلسطينيين',
            'financial-administration' => 'الاداره الماليه',
        ];

        $branchOperationsPermissions = [
            // 'branch-manager'             => "مدير الفرع - نقيب",
            // 'doctor-requests'            => 'طلبات الاطباء',
            'manage-doctors'             => 'الاطباء',
            // 'doctor-practice-permits'    => 'اذونات المزاوله',
            // 'manage-medical-licenses'    => 'تراخيص المنشات الطبيه',
            // 'branch-reports'             => 'التقارير',
            'financial-branch'    => 'الاداره المالية',
            // 'approve-licences-branch'    => 'الموافقه علي التراخيص الفرعيه',
            // 'total_invoices'             => 'الفواتير الكليه',
            'manage-doctor-transfers'   => 'نقل الاطباء'
        ];

        /**
         * 1) Create/Update roles with display_name
         */
        foreach ($rolesData as $roleData) {
            // 'name' must be unique
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],            // Searching by 'name'
                ['display_name' => $roleData['display_name']] // set if creating
            );
            // Update display_name if it already existed
            $role->update(['display_name' => $roleData['display_name']]);
        }

        // Retrieve the roles by their English names for reference
        $generalAdminRole    = Role::where('name', 'general_admin')->first();
        $branchOperationsRole = Role::where('name', 'branch_operations')->first();

        /**
         * 2) Create/Update permissions and assign to the corresponding roles
         */
        // "إدارة عامة"
        foreach ($generalAdministrationPermissions as $permName => $permDisplay) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName],
                ['display_name' => $permDisplay]
            );
            $permission->update(['display_name' => $permDisplay]);

            $generalAdminRole->givePermissionTo($permission);
        }

        foreach ($branchOperationsPermissions as $permName => $permDisplay) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName],
                ['display_name' => $permDisplay]
            );
            $permission->update(['display_name' => $permDisplay]);
            $branchOperationsRole->givePermissionTo($permission);
        }

 
        $firstUser = User::first();
        if ($firstUser) {
            $allPermissions = Permission::all();
            $allRoles = Role::all();
            $firstUser->syncPermissions($allPermissions);
            $firstUser->syncRoles($allRoles);
        }


        $secondUser = User::find(2);
        if ($secondUser) {
            $secondUser->branch_id = 1;
            $secondUser->syncRoles([$branchOperationsRole]);
            // get all permissions for role 2
            $permissions = $branchOperationsRole->permissions;
            $secondUser->syncPermissions($permissions);
            $secondUser->branches()->attach([5]);
            $secondUser->save();
        }
        
    }
}
