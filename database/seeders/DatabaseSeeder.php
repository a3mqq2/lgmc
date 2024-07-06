<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@demo.com',
            'password' => '123123123',
        ]);



        $permission = Permission::create([
            'name' => 'users',
            'display_name' => "الموظفين",
        ]);

        $permission = Permission::create([
            'display_name' => 'التخصصات',
            'name' => "specialties",
        ]);


        $permission = Permission::create([
            'display_name' => 'الفروع',
            'name' => "branches",
        ]);



        $permission = Permission::create([
            'display_name' => 'تراخيص المنشات الطبية',
            'name' => "medical_facility_admin",
        ]);

   


        $permission = Permission::create([
            'display_name' => 'اذونات المزاولة للاطباء ادارة',
            'name' => "doctors_licences_admin",
        ]);

 


        $permission = Permission::create([
            'display_name' => 'الادارة المالية',
            'name' => "admin_accounting",
        ]);

        


        $permission = Permission::create([
            'display_name' => ' اعدادات التسجيل ',
            'name' => "settings",
        ]);


        $permission = Permission::create([
            'display_name' => ' السجلات الامنية  ',
            'name' => "logs",
        ]);


        $permission = Permission::create([
            'display_name' => '  ملفات المنشات الطبية  - الادارة   ',
            'name' => "medical_facilities_admin",
        ]);


        $permission = Permission::create([
            'display_name' => '  ملفات الاطباء  - الادارة   ',
            'name' => "doctors_admin",
        ]);


        $permission = Permission::create([
            'display_name' => '  تقارير   - الادارة   ',
            'name' => "admin_reports",
        ]);


        

        

        
        
        $user->givePermissionTo('users');
        $user->givePermissionTo('specialties');
        $user->givePermissionTo('branches');
        $user->givePermissionTo('medical_facility_admin');
        $user->givePermissionTo('doctors_licences_admin');
        $user->givePermissionTo('admin_accounting');
        $user->givePermissionTo('settings');
        $user->givePermissionTo('medical_facilities_admin');
        $user->givePermissionTo('doctors_admin');
        $user->givePermissionTo('admin_reports');



        $permission2 = Permission::create([
            'display_name' => "اذونات مزاولة الاطباء",
            'name' => "doctor_licences",
        ]);


        $permission2 = Permission::create([
            'display_name' => "ادارة ملفات الاطباء للفروع",
            'name' => "doctors",
        ]);


        $permission2 = Permission::create([
            'display_name' => "المنشات الطبية  للفروع",
            'name' => "medical_facilties",
        ]);


        $permission2 = Permission::create([
            'display_name' => "التراخيص  للمنشات الطبية للفروع",
            'name' => "medical_faciltiesـlicences",
        ]);

        $permission2 = Permission::create([
            'display_name' => " الادارة المالية  للفروع",
            'name' => "branch_accounting",
        ]);


   

        $permission2 = Permission::create([
            'display_name' => " التقارير ",
            'name' => "branch_reports",
        ]);



        $user->givePermissionTo('doctor_licences');
        $user->givePermissionTo('doctors');
        $user->givePermissionTo('medical_facilties');
        $user->givePermissionTo('branch_reports');
        $user->givePermissionTo('branch_accounting');
        $user->givePermissionTo('medical_faciltiesـlicences');

    }
}
