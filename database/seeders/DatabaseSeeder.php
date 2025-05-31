<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AcademicDegree;
use App\Models\University;
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




        
        DB::table('doctor_ranks')->insert([
            ['name' => 'ممارس عام', 'doctor_type' => "libyan"],  
            ['name' => 'طبيب ممارس ', 'doctor_type' => "libyan"],      
            ['name' => 'أخصائي ثاني ', 'doctor_type' => "libyan"],      
            ['name' => 'أخصائي اول ', 'doctor_type' => "libyan"],      
            ['name' => 'استشاري ثاني', 'doctor_type' => "libyan"],        
            ['name' => 'استشاري اول ', 'doctor_type' => "libyan"],        


            ['name' => 'ممارس عام', 'doctor_type' => "foreign"],      
            ['name' => 'طبيب ممارس ', 'doctor_type' => "foreign"],      
            ['name' => 'أخصائي ', 'doctor_type' => "foreign"],      
            ['name' => 'استشاري ', 'doctor_type' => "foreign"],        


            ['name' => 'أخصائي ', 'doctor_type' => "visitor"],      
            ['name' => 'استشاري ', 'doctor_type' => "visitor"],        
            ['name' => 'استشاري دقيق ', 'doctor_type' => "visitor"],        


            ['name' => ' ممارس عام', 'doctor_type' => 'palestinian'],      
            ['name' => 'طبيب ممارس ', 'doctor_type' => 'palestinian'],      
            ['name' => 'أخصائي ', 'doctor_type' => 'palestinian'],      
            ['name' => 'استشاري ', 'doctor_type' => 'palestinian'],          

        ]);


        $this->call(PricingSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AcademicDegreeSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(SpecialtySeeder::class);
        $this->call(FileTypeSeeder::class);
        $this->call(UniversitySeeder::class);
        // $this->call(ArabUniversitiesSeeder::class);
        $this->call(VaultSeeder::class);
        $this->call(MedicalFacilityTypeSeeder::class); // ← 👈 لازم تجي قبل
        
        $this->call(MedicalServiceFilesSeeder::class); // ← ✅ بعدها
        $this->call(PrivateClinicFilesSeeder::class);  // ← ✅ بعدها
        $this->call(SignatureSeeder::class);
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
