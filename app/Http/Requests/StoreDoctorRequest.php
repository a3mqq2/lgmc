<?php

namespace App\Http\Requests;

use App\Models\Blacklist;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {


            $routeName = Route::currentRouteName();
            $isLocalEnv = env('APP_ENV') === 'local' && env('APP_DEBUG') === true;

            
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'national_number' => [
                'required_if:type,libyan',
                'unique:doctors,national_number',
                // function ($attribute, $value, $fail) {
                //     $gender = $this->input('gender');
                //     $first_digit = substr($value, 0, 1);
                //     if ($gender == 'male' && $first_digit != '1') {
                //         $fail('الرقم الوطني للذكور يجب أن يبدأ بالرقم 1.');
                //     } elseif ($gender == 'female' && $first_digit != '2') {
                //         $fail('الرقم الوطني للإناث يجب أن يبدأ بالرقم 2.');
                //     }
                // },
            ],
            'mother_name' => 'nullable|string|max:255',
            'country_id' => 'required_if:type,foreign|required_if:type,visitor',
            'date_of_birth' => 'nullable',
            'doctor_number' => 'nullable|string|max:255',
            'day' => 'nullable|numeric|min:1|max:31',
            'month' => 'nullable|numeric|min:1|max:12',
            'birth_year' => 'nullable|numeric|min:1900|max:2100',
            'email' => "nullable|unique:doctors,email",
            'marital_status' => 'nullable|string|in:single,married',
            'gender' => 'nullable|string|in:male,female',
            'passport_number' => 'nullable|string|max:20',
            'passport_expiration' => 'nullable|date',
            'graduation_date' => 'nullable',
            'phone' => ['required'],
            'phone_2' => ['nullable'],
            'address' => 'nullable|string|max:255',
            'hand_graduation_id' => 'nullable|numeric',
            'internership_complete' => 'nullable',
            'academic_degree_id' => 'nullable|numeric',
            'certificate_of_excellence_date' => 'nullable|string',
            'doctor_rank_id' => 'nullable|numeric',
            'medical_facilities' => 'nullable|array',
            'academic_degree_univeristy_id' => "required_if:type,libyan|nullable|numeric",
            'institution' => "nullable",
            'specialty_1_id' => 'nullable',
            'specialty_2' => 'nullable',
            'ex_medical_facilities' => 'nullable|array',
            'experience' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'branch_id' => 'nullable|numeric',
            'qualification_university_id' => 'nullable|numeric',
            'documents' => "nullable",
            'type' => "required|in:libyan,palestinian,foreign,visitor",
            'password' => 'nullable|min:6|confirmed',
            'country_graduation_id' => 'nullable|numeric',
            'institution_id' => 'nullable',
            'registered_at' => "nullable",
            'medical_facility_id' => 'nullable',
            'visit_from' => 'nullable',
            'visit_to' => 'nullable',
            // 'g-recaptcha-response' => ($routeName === 'register-store' && !$isLocalEnv) ? 'required|captcha' : '',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'حقل الاسم يجب أن يكون نصاً.',
            'name.max' => 'حقل الاسم لا يجب أن يتجاوز 255 حرفاً.',
            'name_en.required' => 'حقل الاسم باللغة الإنجليزية مطلوب.',
            'name_en.string' => 'حقل الاسم باللغة الإنجليزية يجب أن يكون نصاً.',
            'name_en.max' => 'حقل الاسم باللغة الإنجليزية لا يجب أن يتجاوز 255 حرفاً.',
            'national_number.required_if' => 'حقل الرقم الوطني مطلوب في حال كان نوع الطبيب ليبي.',
            'national_number.digits' => 'حقل الرقم الوطني يجب أن يتكون من 12 رقمًا.',
            'national_number.unique' => 'هذا الرقم الوطني مستخدم بالفعل.',
            'mother_name.required' => 'حقل اسم الأم مطلوب.',
            'mother_name.string' => 'حقل اسم الأم يجب أن يكون نصاً.',
            'mother_name.max' => 'حقل اسم الأم لا يجب أن يتجاوز 255 حرفاً.',
            'country_id.required_if' => 'حقل البلد مطلوب في حال كان نوع الطبيب أجنبي أو زائر.',
            'date_of_birth.required' => 'حقل تاريخ الميلاد مطلوب.',
            'date_of_birth.date' => 'حقل تاريخ الميلاد يجب أن يكون تاريخاً صالحاً.',
            'marital_status.required' => 'حقل الحالة الاجتماعية مطلوب.',
            'marital_status.in' => 'حقل الحالة الاجتماعية غير صالح.',
            'gender.required' => 'حقل الجنس مطلوب.',
            'gender.in' => 'حقل الجنس غير صالح.',
            'passport_number.required' => 'حقل رقم الجواز مطلوب.',
            'passport_number.string' => 'حقل رقم الجواز يجب أن يكون نصاً.',
            'passport_number.max' => 'حقل رقم الجواز لا يجب أن يتجاوز 20 حرفاً.',
            'passport_expiration.required' => 'حقل تاريخ انتهاء الجواز مطلوب.',
            'passport_expiration.date' => 'حقل تاريخ انتهاء الجواز يجب أن يكون تاريخاً صالحاً.',
            'passport_expiration.after' => 'حقل تاريخ انتهاء الجواز يجب أن يكون بعد اليوم.',
            'phone.required' => 'حقل رقم الهاتف مطلوب.',
            'phone.regex' => 'رقم الهاتف غير صالح.',
            'address.required' => 'حقل الإقامة مطلوب.',
            'address.string' => 'حقل الإقامة يجب أن يكون نصاً.',
            'address.max' => 'حقل الإقامة لا يجب أن يتجاوز 255 حرفاً.',
            'hand_graduation_id.required' => 'حقل رقم شهادة التخرج مطلوب.',
            'hand_graduation_id.numeric' => 'حقل رقم شهادة التخرج يجب أن يكون رقمياً.',
            'internership_complete.required' => 'حقل تاريخ إتمام التدريب مطلوب.',
            'internership_complete.date' => 'حقل تاريخ إتمام التدريب يجب أن يكون تاريخاً صالحاً.',
            'academic_degree_id.required' => 'حقل الدرجة الأكاديمية مطلوب.',
            'academic_degree_id.numeric' => 'حقل الدرجة الأكاديمية يجب أن يكون رقمياً.',
            'certificate_of_excellence_date.required' => 'حقل تاريخ شهادة التفوق مطلوب.',
            'certificate_of_excellence_date.date' => 'حقل تاريخ شهادة التفوق يجب أن يكون تاريخاً صالحاً.',
            'doctor_rank_id.required' => 'حقل الصفة الطبيب مطلوب.',
            'doctor_rank_id.numeric' => 'حقل الصفة الطبيب يجب أن يكون رقمياً.',
            'medical_facilities.array' => 'حقل المنشآت طبية يجب أن يكون مصفوفة.',
            'specialty_1_id.numeric' => 'حقل التخصص الأول يجب أن يكون رقمياً.',
            'specialty_2_id.numeric' => 'حقل التخصص الثاني يجب أن يكون رقمياً.',
            'specialty_3_id.numeric' => 'حقل التخصص الثالث يجب أن يكون رقمياً.',
            'ex_medical_facilities.string' => 'حقل المنشآت طبية السابقة يجب أن يكون نصاً.',
            'experience.required' => 'حقل الخبرة مطلوب.',
            'experience.numeric' => 'حقل الخبرة يجب أن يكون رقمياً.',
            'experience.min' => 'حقل الخبرة لا يجب أن يكون أقل من 0.',
            'notes.string' => 'حقل الملاحظات يجب أن يكون نصاً.',
            'branch_id.numeric' => 'حقل الفرع يجب أن يكون رقمياً.',
            'qualification_university_id.required' => 'حقل جامعة التأهيل مطلوب.',
            'qualification_university_id.numeric' => 'حقل جامعة التأهيل يجب أن يكون رقمياً.',
            'doctor_number.required' => 'حقل رقم الطبيب مطلوب.',
            'doctor_number.string' => 'حقل رقم الطبيب يجب أن يكون نصاً.',
            'doctor_number.max' => 'حقل رقم الطبيب لا يجب أن يتجاوز 255 حرفاً.',
            'documents.required' => 'حقل المستندات مطلوب.',
            'type.required' => 'حقل نوع الطبيب مطلوب.',
            'type.in' => 'حقل نوع الطبيب غير صالح.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
        ];
    }
}
