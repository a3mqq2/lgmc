<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Vault;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorVault = new Vault();
        $doctorVault->name = "الحساب العام";
        $doctorVault->balance = 0;
        $doctorVault->openning_balance = 0;
        $doctorVault->save();


        $branches = Branch::all();
        foreach ($branches as $branch) {
            $vault = new Vault();
            $vault->name = "حساب " . $branch->name . ' الأساسي';
            $vault->balance = 0;
            $vault->openning_balance = 0;
            $vault->branch_id = $branch->id;
            $vault->save();
        }


    }
}
