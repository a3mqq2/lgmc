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
        $doctorVault->name = "الخزينة العامة";
        $doctorVault->balance = 0;
        $doctorVault->openning_balance = 0;
        $doctorVault->save();

      

        $branches = Branch::all();
        foreach($branches as $branch)
        {   
            $doctorVault = new Vault();
            $doctorVault->name = "خزينة  - " . $branch->name;
            $doctorVault->balance = 0;
            $doctorVault->openning_balance = 0;
            $doctorVault->branch_id = $branch->id;
            $doctorVault->save();

            $branch->vault_id = $doctorVault->id;
            $branch->save();
        }
    }
}
