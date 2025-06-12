<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {

        $roles = [
            'general_admin'     => 'الإدارة العامة',
            'branch_operations' => 'عمليات الفرع',
        ];

        foreach ($roles as $key => $display) {
            Role::updateOrCreate(
                ['name' => $key],
                ['display_name' => $display, 'guard_name' => 'web']
            );
        }

        $generalAdministrationPermissions = [
            'manage-staff'              => 'إدارة الموظفين',
            'finance-general'           => 'الإدارة العامة المالية',
            'doctor-foreign'            => 'أطباء أجانب',
            'doctor-visitor'            => 'أطباء زوار',
            'doctor-palestinian'        => 'أطباء فلسطينيين',
            'doctor-mails'              => 'أوراق الخارج',
            'documents-view'            => 'عرض مستندات',
            'doctor-finance-view'       => 'عرض الملف المالي للطبيب',
            'practice-permit-manage'    => 'إدارة أذونات المزاولة',
            'manage-medical-facilities' => 'إدارة المنشآت الطبية',
            'addons'                    => 'الإضافات',
        ];

        $branchOperationsPermissions = [
            'doctor-libyan'                    => 'أطباء ليبيين',
            'doctor-foreign'            => 'أطباء أجانب',
            'doctor-visitor'            => 'أطباء زوار',
            'doctor-palestinian'        => 'أطباء فلسطينيين',
            'finance-branch'                   => 'إدارة مالية الفرع',
            'doctor-transfers'                 => 'تحويلات الاطباء',
            'documents-view'                   => 'عرض مستندات',
            'doctor-finance-view'              => 'عرض الملف المالي للطبيب',
            'practice-permit-manage'           => 'إدارة أذونات المزاولة',
            'manage-medical-facilities-branch' => 'إدارة منشآت الفرع',
            'addons'                           => 'الإضافات',
        ];

        $generalRole = Role::findByName('general_admin');
        $branchRole  = Role::findByName('branch_operations');

        foreach ($generalAdministrationPermissions as $name => $display) {
            $perm = Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $display, 'guard_name' => 'web']
            );
            $generalRole->givePermissionTo($perm);
        }

        foreach ($branchOperationsPermissions as $name => $display) {
            $perm = Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $display, 'guard_name' => 'web']
            );
            $branchRole->givePermissionTo($perm);
        }


        User::first()?->syncRoles(['general_admin','branch_operations']);
        User::first()?->syncPermissions(Permission::all());
    }
}
