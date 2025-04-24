<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['name' => 'طرابلس', 'code' => 'TIP', 'phone' => '0912345678', 'city' => 'طرابلس'],
            ['name' => 'بنغازي', 'code' => 'BEN', 'phone' => '0923456789', 'city' => 'بنغازي'],
            ['name' => 'الجبل الآخضر', 'code' => 'AJK', 'phone' => '0956789012', 'city' => 'البيضاء'],
            ['name' => 'درنة', 'code' => 'DER', 'phone' => '0967890123', 'city' => 'درنة'],
            ['name' => 'مصراتة', 'code' => 'MIS', 'phone' => '0978901234', 'city' => 'مصراتة'],
            ['name' => 'صرمان', 'code' => 'SRM', 'phone' => '0989012345', 'city' => 'صرمان'],
        ];

        $user = User::first();
        foreach ($branches as $branch) {
            $branch = Branch::updateOrCreate(
                ['code' => $branch['code']], 
                $branch  
            );

            $user->branches()->attach($branch);
        }


       
    }
}
