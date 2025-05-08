<?php
namespace Database\Seeders;

use App\Models\Pricing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // $dynamicPricings = [
        //     ['name' => 'طبيب ممارس', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'اخصائي اول', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'استشاري', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'طبيب ممارس', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'اخصائي اول', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'استشاري', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'طبيب ممارس', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 100, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'اخصائي اول', 'amount' => 100, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'استشاري', 'amount' => 120, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 120, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'طبيب ممارس', 'amount' => 120, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 120, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 150, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'اخصائي اول', 'amount' => 150, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'استشاري', 'amount' => 200, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 200, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'اخصائي', 'amount' => 200, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
        //     ['name' => 'استشاري', 'amount' => 300, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 400, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
        //     ['name' => 'اخصائي', 'amount' => 400, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
        //     ['name' => 'استشاري', 'amount' => 500, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 600, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],

            
        //     ['name' => 'طبيب ممارس', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'اخصائي اول', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'استشاري', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'طبيب ممارس', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'طبيب ممارس تخصصي', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'اخصائي ثاني', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'اخصائي اول', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'استشاري', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'استشاري تخصص دقيق', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
       
        //     ['name' => 'تجديد اذن نشاط', 'amount' => 500, 'type' => 'license', 'entity_type' => 'medical_facility'],
        //     ['name' => 'تجديد اذن نشاط - لاول مره', 'amount' => 2000, 'type' => 'license', 'entity_type' => 'medical_facility'],
        //     ['name' => 'غرامة تآخير', 'amount' => 1000, 'type' => 'penalty', 'entity_type' => 'medical_facility'],

        // ];

        // foreach ($dynamicPricings as $pricing) {
        //     $pricing['is_local'] = 0;
        //     $pricing['need_file'] = 0;
        //     $pricing['created_at'] = now();
        //     $pricing['updated_at'] = now();
        //     Pricing::create($pricing);
        // }




        // $fixedPricings = [
        //     [ 'name' => 'حسن سيرة وسلوك / Certificate of Good Standing', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'حسن سيرة وسلوك / Certificate of Good Standing', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'حسن سيرة وسلوك / Certificate of Good Standing', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'السنة الثانية امتياز بعد التخرج', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'تعريف - Certificate', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'تعريف - Certificate', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     [ 'name' => 'تعريف - Certificate', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'License of Practic / أذن مزاولة المهنة', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     ['name' => 'License of Practic / أذن مزاولة المهنة', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     [ 'name' => 'License of Practic / أذن مزاولة المهنة', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     [ 'name' => 'بريد الكتروني / Email', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     [ 'name' => 'بريد الكتروني / Email', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     [ 'name' => 'بريد الكتروني / Email', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     [ 'name' => 'رسالة تحقق بخصوص عمل (تتضمن فترة تدريب)', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     [ 'name' => 'رسالة تحقق بخصوص عمل (تتضمن فترة تدريب)', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     [ 'name' => 'رسالة تحقق بخصوص عمل (تتضمن فترة تدريب)', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     [ 'name' => 'رسائل الجامعة - University Letters', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     ['name' => 'رسائل الجامعة - University Letters', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     [ 'name' => 'رسائل الجامعة - University Letters', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        //     [ 'name' => 'رسالة تحقق من تسجيل اخصائي او استشاري', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
        //     [ 'name' => 'رسالة تحقق من تسجيل اخصائي او استشاري', 'amount' => 100.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
        //     ['name' => 'رسالة تحقق من تسجيل اخصائي او استشاري', 'amount' => 50.00, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
        // ];

        // foreach ($fixedPricings as &$pricing) {
        //     $pricing['is_local'] = 0;
        //     $pricing['need_file'] = 0;
        //     $pricing['created_at'] = now();
        //     $pricing['updated_at'] = now();
        // }

        // DB::table('pricings')->insertOrIgnore($fixedPricings);




        $list_of_pricings_foreign = [
                [
                    "name" => "فتح ملف اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 50,
                    "type" => "service",
                    "entity_type" => "doctor",
                    'category' => "open_file",
                ],
                [
                    "name" => "اشتراك سنوي اجنبي ممارس مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 50,
                    "type" => "membership",
                    "doctor_rank_id" => 5,
                ],
                [
                    "name" => "اشتراك سنوي اجنبي اخصائي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "membership",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "اشتراك سنوي اجنبي استشاري مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 120,
                    "type" => "membership",
                    "doctor_rank_id" => 7,
                ]
            ];


            $list_of_pricings_foreign_mails = [
                [
                    "name" => "رخصة اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "تعريف - Certificate / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "حسن سيرة وسلوك / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "رسالة تحقق بخصوص عمل (تتضمن فترة تدريب) / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "رسائل الجامعة - University Letters / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "رسالة تحقق من تسجيل اخصائي او استشاري / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
                [
                    "name" => "بريد الكتروني / اجنبي مقيم",
                    "doctor_type" => "foreign",
                    "amount" => 100,
                    "type" => "mail",
                    "doctor_rank_id" => 6,
                ],
            ];




            // pricing for libyan doctors

            $list_of_pricings_libyan = [
                [
                    "name" => "فتح ملف ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 25,
                    "type" => "service",
                    "entity_type" => "doctor",
                    'category' => "open_file",
                ],
                [
                    "name" => "اشتراك سنوي ليبي ممارس",
                    "doctor_type" => "libyan",
                    "amount" => 25,
                    "type" => "membership",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "اشتراك سنوي ليبي ممارس",
                    "doctor_type" => "libyan",
                    "amount" => 25,
                    "type" => "membership",
                    "doctor_rank_id" => 2,
                ],
                [
                    "name" => "اشتراك سنوي ليبي اخصائي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "membership",
                    "doctor_rank_id" => 3,
                ],
                [
                    "name" => "اشتراك سنوي ليبي استشاري",
                    "doctor_type" => "libyan",
                    "amount" => 60,
                    "type" => "membership",
                    "doctor_rank_id" => 4,
                ]
            ];


            $list_of_pricings_libyan_mails = [
                [
                    "name" => "رخصة ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "تعريف - Certificate / ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "حسن سيرة وسلوك / ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "رسالة تحقق بخصوص عمل (تتضمن فترة تدريب) / ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "رسائل الجامعة - University Letters / ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 1,
                ],
                [
                    "name" => "رسالة تحقق من تسجيل اخصائي او استشاري / ليبي",
                    "doctor_type" => "libyan",
                    "amount" => 50,
                    "type" => "mail",
                    'doctor_rank_id' => 1,
                ],
                [
                    'name' => 'بريد الكتروني / ليبي',
                    'doctor_type' => 'libyan',
                    'amount' => 50,
                    'type' => 'mail',
                ],
            ];





            $list_of_pricings_palestinian = [
                [
                    "name" => "فتح ملف فلسطيني",
                    "doctor_type" => "palestinian",
                    "amount" => 25,
                    "type" => "service",
                    "entity_type" => "doctor",
                    'category' => "open_file",
                ],
                [
                    "name" => "اشتراك سنوي فلسطيني ممارس",
                    "doctor_type" => "palestinian",
                    "amount" => 25,
                    "type" => "membership",
                    "doctor_rank_id" => 10,
                ],
                [
                    "name" => "اشتراك سنوي فلسطيني اخصائي",
                    "doctor_type" => "palestinian",
                    "amount" => 25,
                    "type" => "membership",
                    'doctor_rank_id' => 11,
                ],
                [
                    'name' => 'اشتراك سنوي فلسطيني استشاري',
                    'doctor_type' => 'palestinian',
                    'amount' => 60,
                    'type' => 'membership',
                    'doctor_rank_id' => 12,
                ]
            ];



            $list_of_pricings_palestinian_mails = [
                [
                    "name" => "رخصة فلسطيني",
                    "doctor_type" => "palestinian",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 10,
                ],
                [
                    "name" => "تعريف - Certificate / فلسطيني",
                    "doctor_type" => "palestinian",
                    "amount" => 50,
                    "type" => "mail",
                    "doctor_rank_id" => 10,
                ],
                [
                    "name" => "حسن سيرة وسلوك / فلسطيني",
                    "doctor_type" => "palestinian",
                    "amount" => 50,
                    "type" => "mail",
                    'doctor_rank_id' => 10,
                ],
                [
                    'name' => 'رسالة تحقق بخصوص عمل (تتضمن فترة تدريب) / فلسطيني',
                    'doctor_type' => 'palestinian',
                    'amount' => 50,
                    'type' => 'mail',
                ],
                [
                    'name' => 'رسائل الجامعة - University Letters / فلسطيني',
                    'doctor_type' => 'palestinian',
                    'amount' => 50,
                    'type' => 'mail',
                ],
                [
                    'name' => 'رسالة تحقق من تسجيل اخصائي او استشاري / فلسطيني',
                    'doctor_type' => 'palestinian',
                    'amount' => 50,
                    'type' => 'mail',
                ],
                [
                    'name' => 'بريد الكتروني / فلسطيني',
                    'doctor_type' => 'palestinian',
                    'amount' => 50,
                    'type' => 'mail',
                ],
            ];






            foreach(array_merge($list_of_pricings_foreign, $list_of_pricings_foreign_mails) as $pricing)
            {
                $pricing['is_local'] = 0;
                $pricing['need_file'] = 0;
                $pricing['created_at'] = now();
                $pricing['updated_at'] = now();
                Pricing::create($pricing);
            }
            

    }
}