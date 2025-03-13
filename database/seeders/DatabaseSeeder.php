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


        $user = \App\Models\User::factory()->create([
            'name' => 'ولاء',
            'email' => 'walaa@lgmc.ly',
            'password' => '123123123',
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'نعمة',
            'email' => 'naema@lgmc.ly',
            'password' => '123123123',
        ]);


        
        DB::table('doctor_ranks')->insert([
            ['name' => 'طبيب ممارس'], // 1       
            ['name' => 'طبيب ممارس تخصصي'],  // 2      
            ['name' => 'أخصائي أول'],       // 3
            ['name' => 'أخصائي ثاني'],       // 4
            ['name' => 'استشاري اول'],        // 5
            ['name' => 'استشاري'],  // 6     
        ]);

        $this->call(PricingSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AcademicDegreeSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(SpecialtySeeder::class);
        $this->call(FileTypeSeeder::class);
        $this->call(ArabUniversitiesSeeder::class);
        $this->call(VaultSeeder::class);
        $this->call(MedicalFacilityTypeSeeder::class);
        $this->call(TransactionTypesSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
