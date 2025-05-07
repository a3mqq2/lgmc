<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AcademicDegree;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            'name' => 'Administrator',
            'email' => 'admin@demo.com',
            'password' => '123123123',
        ]);


        $walaa = \App\Models\User::factory()->create([
            'name' => 'ولاء',
            'email' => 'walaa@lgmc.ly',
            'password' => '123123123',
        ]);



        
        DB::table('doctor_ranks')->insert([
            ['name' => 'ممارس عام'],
            ['name' => 'طبيب ممارس '],  
            ['name' => 'أخصائي ثاني'],      
            ['name' => 'أخصائي اول'],       
            ['name' => 'استشاري ثاني'],        
            ['name' => 'استشاري اول'],   
        ]);


        $this->call(PricingSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AcademicDegreeSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(SpecialtySeeder::class);
        $this->call(FileTypeSeeder::class);
        $this->call(ArabUniversitiesSeeder::class);
        $this->call(VaultSeeder::class);
        $this->call(MedicalFacilityTypeSeeder::class); // ← 👈 لازم تجي قبل
        
        $this->call(MedicalServiceFilesSeeder::class); // ← ✅ بعدها
        $this->call(PrivateClinicFilesSeeder::class);  // ← ✅ بعدها
        
        $this->call(TransactionTypesSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(DoctorMailPermission::class);
        $this->call(ServicePricingSeeder::class);
        $this->call(LibyanFiles::class);
        $this->call(ForeignDoctorFileTypeSeeder::class);
        $this->call(PalestinianDoctorFileTypeSeeder::class);
        $this->call(VisitorDoctorFileTypeSeeder::class);
        $this->call(PermissionForDoctors::class);
    }
}
