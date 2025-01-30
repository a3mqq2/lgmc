<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalFacilityRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مصرح له بتنفيذ هذا الطلب.
     */
    public function authorize(): bool
    {
        return auth()->check(); // السماح فقط للمستخدمين المسجلين
    }

    /**
     * قواعد التحقق من البيانات.
     */
    public function rules(): array
    {
        return [
            'serial_number' => 'required|string|max:255|unique:medical_facilities,serial_number,' . $this->medical_facility,
            'name' => 'required|string|max:255',
            'commerical_number' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'required|string|regex:/^\d{10}$/',
            'activity_start_date' => 'required|date',
            'activity_type' => 'required|in:commercial_record,negative_certificate',
            'manager_id' => 'nullable|exists:doctors,id',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // الحد الأقصى 2 ميجابايت
        ];
    }

    /**
     * رسائل الخطأ المخصصة لكل قاعدة تحقق.
     */
    public function messages(): array
    {
        return [
            'serial_number.required' => 'رقم المنشأة مطلوب.',
            'serial_number.unique' => 'رقم المنشأة مستخدم بالفعل.',
            'name.required' => 'اسم المنشأة مطلوب.',
            'commerical_number.required' => 'رقم السجل التجاري مطلوب.',
            'branch_id.exists' => 'الفرع المحدد غير صحيح.',
            'medical_facility_type_id.required' => 'يجب اختيار نوع المنشأة الطبية.',
            'medical_facility_type_id.exists' => 'نوع المنشأة الطبية غير صالح.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.regex' => 'رقم الهاتف يجب أن يتكون من 10 أرقام فقط.',
            'activity_start_date.required' => 'تاريخ بدء النشاط مطلوب.',
            'activity_start_date.date' => 'يجب أن يكون تاريخ بدء النشاط تاريخًا صحيحًا.',
            'activity_type.required' => 'يجب اختيار نوع النشاط.',
            'activity_type.in' => 'نوع النشاط غير صالح.',
            'manager_id.exists' => 'مالك النشاط المحدد غير صحيح.',

        ];
    }
}
