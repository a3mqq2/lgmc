<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pricing;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pricings = [
            ['name' => 'طبيب ممارس', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اخصائي ثاني', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اخصائي اول', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'استشاري', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'طبيب ممارس', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اخصائي ثاني', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اخصائي اول', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'استشاري', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'طبيب ممارس', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اخصائي ثاني', 'amount' => 100, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اخصائي اول', 'amount' => 100, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'استشاري', 'amount' => 120, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 120, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'طبيب ممارس', 'amount' => 120, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 120, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اخصائي ثاني', 'amount' => 150, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اخصائي اول', 'amount' => 150, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'استشاري', 'amount' => 200, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 200, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اخصائي', 'amount' => 200, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'استشاري', 'amount' => 300, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 400, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'اخصائي', 'amount' => 400, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'استشاري', 'amount' => 500, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 600, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'visitor'],
            ['name' => 'رسالة حسن سيره وسلوك', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رخصة', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'تعريف', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رسالة تدريب', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رسالة اخصائي', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رسالة استشاري', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رسالة السنة الثانية امتياز', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'البريد الالكتروني', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'تعبئة النماذج ', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'معادلة دبلومة محلية مع التصنيف', 'amount' => 350, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'رسالة حسن سيره وسلوك', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'رخصة', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'تعريف', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'رسالة تدريب', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'رسالة اخصائي', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'رسالة استشاري', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'رسالة السنة الثانية امتياز', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'البريد الالكتروني', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'تعبئة النماذج ', 'amount' => 200, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'معادلة دبلومة محلية مع التصنيف', 'amount' => 700, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'اصدار بطاقة', 'amount' => 30, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اصدار بطاقة', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],
            ['name' => 'طبيب ممارس', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 25, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اخصائي ثاني', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اخصائي اول', 'amount' => 50, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'استشاري', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 60, 'type' => 'membership', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'طبيب ممارس', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'طبيب ممارس تخصصي', 'amount' => 60, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اخصائي ثاني', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اخصائي اول', 'amount' => 75, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'استشاري', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'استشاري تخصص دقيق', 'amount' => 100, 'type' => 'license', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رسالة حسن سيره وسلوك', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رخصة', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'تعريف', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رسالة تدريب', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رسالة اخصائي', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رسالة استشاري', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'رسالة السنة الثانية امتياز', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'البريد الالكتروني', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'تعبئة النماذج ', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'معادلة دبلومة محلية مع التصنيف', 'amount' => 350, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اصدار بطاقة', 'amount' => 30, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اذن نشاط لآول مره', 'amount' => 2000, 'type' => 'license', 'entity_type' => 'medical_facility'],
            ['name' => 'تجديد اذن نشاط', 'amount' => 500, 'type' => 'license', 'entity_type' => 'medical_facility'],
            ['name' => 'غرامة تآخير', 'amount' => 1000, 'type' => 'penalty', 'entity_type' => 'medical_facility'],



            ['name' => 'قتح ملف', 'amount' => 20, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'قتح ملف', 'amount' => 20, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'قتح ملف', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],


            ['name' => 'اصدار بطاقة', 'amount' => 30, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'اصدار بطاقة', 'amount' => 30, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'اصدار بطاقة', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign'],


        ];

        foreach ($pricings as $pricing) {
            Pricing::create($pricing);
        }
    }
}