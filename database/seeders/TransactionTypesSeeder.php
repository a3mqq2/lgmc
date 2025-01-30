<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaction_types = ['رسوم عضوية', 'رسوم اذن مزاولة', 'غرامة', 'طلب','تحويل اموال'];

        TransactionType::create([
            "name" => "رسوم عضوية",
            "type" => "deposit",
        ]);

        TransactionType::create([
            "name" => "رسوم اذن مزاولة",
            "type" => "deposit",
        ]);

        TransactionType::create([
            "name" => "غرامة",
            "type" => "deposit",
        ]);

        TransactionType::create([
            "name" => "طلب",
            "type" => "deposit",
        ]);


        TransactionType::create([
            "name" => "تحويل اموال",
            "type" => "deposit",
        ]);


        TransactionType::create([
            "name" => "تحويل اموال",
            "type" => "withdrawal",
        ]);


        TransactionType::create([
            "name" => "فواتير كلية",
            "type" => "deposit",
        ]);

    }
}
