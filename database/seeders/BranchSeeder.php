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
            ['name' => 'سبها', 'code' => 'SEB', 'phone' => '0934567890', 'city' => 'سبها'],
            ['name' => 'طبرق', 'code' => 'TOB', 'phone' => '0945678901', 'city' => 'طبرق'],
            ['name' => 'الجبل الآخضر', 'code' => 'BYD', 'phone' => '0956789012', 'city' => 'البيضاء'],
            ['name' => 'درنة', 'code' => 'DER', 'phone' => '0967890123', 'city' => 'درنة'],
            ['name' => 'مصراتة', 'code' => 'MIS', 'phone' => '0978901234', 'city' => 'مصراتة'],
            ['name' => 'غريان', 'code' => 'GHR', 'phone' => '0989012345', 'city' => 'غريان'],
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
